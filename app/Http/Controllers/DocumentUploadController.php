<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentUploadRequest;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentUploadController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        // Index existing documents by type
        $documents = Document::where('user_id', $user->id)->get()->keyBy('type');

        return view('pelamar.documents.index', [
            'user' => $user,
            'documents' => $documents,
            'types' => Document::TYPES,
            'requiredTypes' => Document::REQUIRED_TYPES,
            'hints' => Document::HINTS,
        ]);
    }

    /**
     * Upload single document.
     */
    public function store(DocumentUploadRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $type = $request->input('type');
        $file = $request->file('file');

        // 1. Validate type
        if (!array_key_exists($type, Document::TYPES)) {
            return back()->withErrors(['file' => 'Jenis dokumen tidak valid.']);
        }

        // 2. Delete existing document (replace mode)
        // Disk file akan auto-delete via booted() di model
        $existing = Document::where('user_id', $user->id)->where('type', $type)->first();
        if ($existing) {
            $existing->delete();
        }

        // 3. Generate unique filename
        $extension = $file->getClientOriginalExtension();
        $filename = sprintf(
            '%s_%s_%s.%s',
            $user->id,
            $type,
            Str::random(10),
            $extension
        );

        // 4. Store file di disk 'documents' (konsisten dengan admin upload)
        $file->storeAs('', $filename, 'documents');

        // 5. Create database record
        Document::create([
            'user_id' => $user->id,
            'type' => $type,
            'file_path' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        // 6. Log untuk audit
        Log::info('Document uploaded by pelamar', [
            'user_id' => $user->id,
            'type' => $type,
            'filename' => $filename,
            'size' => $file->getSize(),
            'ip' => $request->ip(),
        ]);

        $typeLabel = Document::TYPES[$type] ?? $type;

        return back()->with('status', "Dokumen {$typeLabel} berhasil diupload.");
    }

    /**
     * Delete a document.
     */
    public function destroy(Request $request, Document $document): RedirectResponse
    {
        // Security: only owner can delete
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan menghapus dokumen ini.');
        }

        // Prevent delete if application is verified
        if ($request->user()->application && $request->user()->application->status === 'verified') {
            return back()->withErrors(['general' => 'Dokumen tidak dapat dihapus setelah aplikasi diverifikasi.']);
        }

        $typeLabel = $document->getTypeLabel();

        // Delete record (file auto-deleted via booted() in model)
        $document->delete();

        Log::info('Document deleted by pelamar', [
            'user_id' => Auth::id(),
            'document_id' => $document->id,
            'type' => $document->type,
        ]);

        return back()->with('status', "Dokumen {$typeLabel} berhasil dihapus.");
    }
}
