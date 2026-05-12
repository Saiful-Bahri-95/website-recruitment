<?php

namespace App\Http\Controllers;

use App\Models\PdfGeneration;
use App\Services\PdfGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function __construct(
        private PdfGeneratorService $pdfService
    ) {}

    /**
     * View PDF di browser
     */
    public function view(PdfGeneration $pdf)
    {
        $this->authorize($pdf);

        if (!$pdf->fileExists()) {
            abort(404, 'PDF tidak ditemukan atau sudah expired');
        }

        return response()->file(Storage::disk('generated_pdfs')->path($pdf->file_path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $pdf->file_name . '"',
        ]);
    }

    /**
     * Download PDF
     */
    public function download(PdfGeneration $pdf)
    {
        $this->authorize($pdf);

        if (!$pdf->fileExists()) {
            abort(404, 'PDF tidak ditemukan atau sudah expired');
        }

        Log::info('PDF downloaded', [
            'pdf_id' => $pdf->id,
            'user_id' => $pdf->user_id,
            'downloaded_by' => Auth::id(),
            'ip' => request()->ip(),
        ]);

        return response()->download(Storage::disk('generated_pdfs')->path($pdf->file_path), $pdf->file_name);
    }

    /**
     * Permission check - hanya admin yang bisa akses generated PDFs
     */
    private function authorize(PdfGeneration $pdf): void
    {
        if (!Auth::check()) {
            abort(401);
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa akses PDF gabungan');
        }
    }
}
