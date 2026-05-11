<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentAccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * View dokumen di browser (preview PDF/image).
     */
    public function view(Document $document, Request $request)
    {
        $this->authorize($document);

        $this->logAccess($document, 'view', $request);

        if (!$document->fileExists()) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file(
            Storage::disk('documents')->path($document->file_path),
            [
                'Content-Type' => $document->mime_type ?? 'application/octet-stream',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }

    /**
     * Download dokumen (force download).
     */
    public function download(Document $document, Request $request)
    {
        $this->authorize($document);

        $this->logAccess($document, 'download', $request);

        if (!$document->fileExists()) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->download(
            Storage::disk('documents')->path($document->file_path),
            $document->original_name,
            ['Content-Disposition' => 'attachment; filename="' . $document->original_name . '"']
        );
    }

    /**
     * Cek apakah user authorized untuk akses dokumen.
     */
    private function authorize(Document $document): void
    {
        if (!Auth::check()) {
            abort(401, 'Anda harus login terlebih dahulu');
        }

        $user = Auth::user();

        // Admin bisa akses semua dokumen
        if ($user->role === 'admin') {
            return;
        }

        // Pelamar hanya bisa akses dokumen miliknya sendiri
        if ($document->user_id !== $user->id) {
            abort(403, 'Anda tidak punya akses ke dokumen ini');
        }
    }

    /**
     * Log akses dokumen untuk audit trail.
     */
    private function logAccess(Document $document, string $action, Request $request): void
    {
        try {
            DocumentAccessLog::create([
                'document_id' => $document->id,
                'user_id' => Auth::id(),
                'action' => $action,
                'ip_address' => $request->ip(),
                'user_agent' => substr($request->userAgent() ?? '', 0, 500),
            ]);
        } catch (\Exception $e) {
            // Jangan biarkan logging error menghentikan akses dokumen
            Log::error('Failed to log document access', [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
