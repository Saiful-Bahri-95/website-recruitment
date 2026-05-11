<?php

namespace App\Services;

use App\Models\PdfGeneration;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PdfGeneratorService
{
    /**
     * Generate PDF profile lengkap untuk pelamar.
     */
    public function generateForUser(User $user): PdfGeneration
    {
        // Load semua relasi yang dibutuhkan
        $user->load([
            'biodata',
            'educations',
            'workExperiences',
            'emergencyContacts',
            'documents' => function ($query) {
                $query->orderBy('type');
            },
            'application',
        ]);

        // Generate filename unik
        $filename = $this->generateFilename($user);
        $folderPath = date('Y/m');
        $filePath = $folderPath . '/' . $filename;

        // Pastikan folder exists
        $fullFolderPath = storage_path('app/private/generated_pdfs/' . $folderPath);
        if (!is_dir($fullFolderPath)) {
            mkdir($fullFolderPath, 0755, true);
        }

        // Generate PDF
        $pdf = Pdf::loadView('pdfs.pelamar-profile', ['user' => $user])
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'dpi' => 96,
            ]);

        // Save ke disk private
        $fullPath = storage_path('app/private/generated_pdfs/' . $filePath);
        $pdf->save($fullPath);

        // Hitung file size
        $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;

        // Buat record PdfGeneration
        return PdfGeneration::create([
            'user_id' => $user->id,
            'generated_by' => Auth::id(),
            'file_path' => $filePath,
            'file_name' => $filename,
            'file_size' => $fileSize,
            'document_count' => $user->documents->count(),
            'expires_at' => now()->addHours(24), // Auto-cleanup setelah 24 jam
        ]);
    }

    /**
     * Generate filename unik untuk PDF.
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
     * Hapus PDF lama yang sudah expired.
     */
    public function cleanupExpired(): int
    {
        $expired = PdfGeneration::where('expires_at', '<', now())->get();
        $count = 0;

        foreach ($expired as $pdf) {
            $pdf->delete(); // Akan trigger file deletion via Model boot
            $count++;
        }

        return $count;
    }
}
