<?php

namespace App\Services;

use App\Models\PdfGeneration;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;
use Spatie\Browsershot\Browsershot;

class PdfGeneratorService
{
    /**
     * Nama perusahaan untuk footer.
     */
    private const COMPANY_NAME = 'PT Anggita Global Recruitment';

    /**
     * Generate PDF profile lengkap + merge dokumen asli.
     */
    public function generateForUser(User $user): PdfGeneration
    {
        $user->load([
            'biodata',
            'educations',
            'workExperiences',
            'emergencyContacts',
            'documents' => fn($q) => $q->orderBy('type'),
            'application',
        ]);

        $filename = $this->generateFilename($user);
        $folderPath = date('Y/m');
        $filePath = $folderPath . '/' . $filename;

        $fullFolderPath = storage_path('app/private/generated_pdfs/' . $folderPath);
        if (!is_dir($fullFolderPath)) {
            mkdir($fullFolderPath, 0755, true);
        }

        $tempDir = storage_path('app/temp_pdf_' . Str::random(10));
        mkdir($tempDir, 0755, true);

        try {
            // STEP 1: Generate PDF profil (cover + biodata)
            $profilePdfPath = $tempDir . '/profile.pdf';
            $html = view('pdfs.modern.layout', [
                'user' => $user
            ])->render();

            Browsershot::html($html)
                ->format('A4')
                ->margins(0, 0, 0, 0)
                ->showBackground()
                ->emulateMedia('print')
                ->waitUntilNetworkIdle()
                ->timeout(120)
                ->save($profilePdfPath);

            // STEP 2: Convert gambar dokumen ke PDF (dengan header & footer)
            $documentPdfs = [];
            $mergedCount = 0;

            foreach ($user->documents as $document) {
                $sourcePath = storage_path('app/private/documents/' . $document->file_path);

                if (!file_exists($sourcePath)) {
                    Log::warning("File tidak ditemukan saat merge PDF", [
                        'document_id' => $document->id,
                        'file_path' => $document->file_path,
                    ]);
                    continue;
                }

                $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));

                if ($extension === 'pdf') {
                    $documentPdfs[] = [
                        'path' => $sourcePath,
                        'label' => $document->getTypeLabel(),
                    ];
                    $mergedCount++;
                } elseif (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $imagePdfPath = $tempDir . '/img_' . Str::random(8) . '.pdf';
                    try {
                        $this->convertImageToPdf($sourcePath, $imagePdfPath, $document->getTypeLabel());
                        $documentPdfs[] = [
                            'path' => $imagePdfPath,
                            'label' => $document->getTypeLabel(),
                        ];
                        $mergedCount++;
                    } catch (\Exception $e) {
                        Log::error("Gagal convert image ke PDF", [
                            'document_id' => $document->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }

            // STEP 3: Merge semua PDF dengan header dokumen di tiap halaman
            $finalPath = storage_path('app/private/generated_pdfs/' . $filePath);
            $this->mergePdfsWithHeader(
                $profilePdfPath,
                $documentPdfs,
                $finalPath
            );

            $fileSize = file_exists($finalPath) ? filesize($finalPath) : 0;

            return PdfGeneration::create([
                'user_id' => $user->id,
                'generated_by' => Auth::id(),
                'file_path' => $filePath,
                'file_name' => $filename,
                'file_size' => $fileSize,
                'document_count' => $mergedCount,
                'expires_at' => now()->addHours(24),
            ]);
        } finally {
            $this->deleteDirectory($tempDir);
        }
    }

    /**
     * Convert gambar ke PDF dengan header dokumen + footer perusahaan.
     */
    private function convertImageToPdf(string $imagePath, string $outputPath, string $label): void
    {
        $imageInfo = @getimagesize($imagePath);
        if (!$imageInfo) {
            throw new \Exception("Tidak bisa baca gambar: $imagePath");
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        $orientation = $width > $height ? 'L' : 'P';
        $pdf = new Fpdi($orientation, 'mm', 'A4');
        $pdf->AddPage();

        $pageWidth = $orientation === 'L' ? 297 : 210;
        $pageHeight = $orientation === 'L' ? 210 : 297;
        $margin = 15;

        // Header: nama jenis dokumen
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->SetTextColor(30, 58, 138);
        $pdf->Cell(0, 8, $label, 0, 1, 'C');
        $pdf->SetDrawColor(30, 58, 138);
        $pdf->SetLineWidth(0.5);
        $pdf->Line($margin, $pdf->GetY() + 2, $pageWidth - $margin, $pdf->GetY() + 2);
        $pdf->Ln(7);

        // Hitung area gambar
        $availableWidth = $pageWidth - (2 * $margin);
        $availableHeight = $pageHeight - $pdf->GetY() - 25;

        $widthMm = $width / 3.78;
        $heightMm = $height / 3.78;
        $ratio = min($availableWidth / $widthMm, $availableHeight / $heightMm);
        $finalWidth = $widthMm * $ratio;
        $finalHeight = $heightMm * $ratio;

        $x = ($pageWidth - $finalWidth) / 2;
        $y = $pdf->GetY();

        $pdf->Image($imagePath, $x, $y, $finalWidth, $finalHeight);

        // Footer perusahaan (sudah dihandle oleh FPDI saat merge nanti)
        $pdf->Output($outputPath, 'F');
    }

    /**
     * Merge profile PDF + dokumen PDFs, dengan header & footer ditambahkan ke tiap halaman dokumen.
     */
    private function mergePdfsWithHeader(string $profilePath, array $documentPdfs, string $outputPath): void
    {
        $merger = new Fpdi();
        $merger->SetAutoPageBreak(false);

        // STEP A: Import semua halaman profile PDF (apa adanya, tanpa modifikasi)
        $pageCount = $merger->setSourceFile($profilePath);
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $template = $merger->importPage($pageNo);
            $size = $merger->getTemplateSize($template);

            $merger->AddPage(
                $size['width'] > $size['height'] ? 'L' : 'P',
                [$size['width'], $size['height']]
            );
            $merger->useTemplate($template);
        }

        // STEP B: Import setiap dokumen PDF, tambahkan HEADER & FOOTER di tiap halaman
        foreach ($documentPdfs as $docInfo) {
            try {
                $docPageCount = $merger->setSourceFile($docInfo['path']);

                for ($pageNo = 1; $pageNo <= $docPageCount; $pageNo++) {
                    $template = $merger->importPage($pageNo);
                    $size = $merger->getTemplateSize($template);

                    $orientation = $size['width'] > $size['height'] ? 'L' : 'P';
                    $merger->AddPage($orientation, [$size['width'], $size['height']]);

                    // Tambahkan template halaman dokumen DENGAN MARGIN ATAS untuk header
                    // Geser template ke bawah agar tidak overlap dengan header kita
                    $merger->useTemplate($template, 0, 12, $size['width'], $size['height'] - 24);

                    // === HEADER: Nama jenis dokumen (di atas halaman) ===
                    $merger->SetXY(10, 5);
                    $merger->SetFont('Helvetica', 'B', 10);
                    $merger->SetTextColor(30, 58, 138);
                    $merger->Cell($size['width'] - 20, 5, $docInfo['label'], 0, 0, 'C');

                    // Garis horizontal di bawah header
                    $merger->SetDrawColor(30, 58, 138);
                    $merger->SetLineWidth(0.3);
                    $merger->Line(10, 11, $size['width'] - 10, 11);

                    // === FOOTER: Nama perusahaan (di bawah halaman) ===
                    $merger->SetXY(10, $size['height'] - 8);
                    $merger->SetFont('Helvetica', 'I', 8);
                    $merger->SetTextColor(120, 120, 120);
                    $merger->Cell($size['width'] - 20, 5, self::COMPANY_NAME, 0, 0, 'C');

                    // Garis tipis di atas footer
                    $merger->SetDrawColor(200, 200, 200);
                    $merger->SetLineWidth(0.2);
                    $merger->Line(10, $size['height'] - 10, $size['width'] - 10, $size['height'] - 10);
                }
            } catch (\Exception $e) {
                Log::warning("Gagal merge PDF dokumen: " . $docInfo['path'], [
                    'error' => $e->getMessage(),
                ]);
                continue;
            }
        }

        $merger->Output($outputPath, 'F');
    }

    /**
     * Generate filename unik.
     */
    private function generateFilename(User $user): string
    {
        $nama = $user->biodata?->nama_lengkap ?? $user->name;
        $namaSlug = Str::slug($nama);
        $timestamp = now()->format('Ymd-His');
        $random = Str::random(6);

        return "Profil_{$namaSlug}_{$timestamp}_{$random}.pdf";
    }

    /**
     * Recursive delete directory.
     */
    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) return;

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($path) ? $this->deleteDirectory($path) : @unlink($path);
        }
        @rmdir($dir);
    }

    /**
     * Cleanup expired PDFs.
     */
    public function cleanupExpired(): int
    {
        $expired = PdfGeneration::where('expires_at', '<', now())->get();
        $count = 0;
        foreach ($expired as $pdf) {
            $pdf->delete();
            $count++;
        }
        return $count;
    }
}
