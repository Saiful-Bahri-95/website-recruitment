<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

class PdfGeneration extends Model
{
    protected $fillable = [
        'user_id',
        'generated_by',
        'file_path',
        'file_name',
        'file_size',
        'document_count',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'file_size' => 'integer',
        'document_count' => 'integer',
    ];

    /**
     * Auto-delete file saat record dihapus
     */
    protected static function booted(): void
    {
        static::deleting(function (PdfGeneration $pdf) {
            if ($pdf->file_path && Storage::disk('generated_pdfs')->exists($pdf->file_path)) {
                Storage::disk('generated_pdfs')->delete($pdf->file_path);
            }
        });
    }

    /**
     * Generate signed URL untuk download PDF
     */
    public function getSecureDownloadUrl(int $expireMinutes = 10): string
    {
        return URL::temporarySignedRoute(
            'pdf.download',
            now()->addMinutes($expireMinutes),
            ['pdf' => $this->id]
        );
    }

    /**
     * Generate signed URL untuk preview PDF
     */
    public function getSecureViewUrl(int $expireMinutes = 10): string
    {
        return URL::temporarySignedRoute(
            'pdf.view',
            now()->addMinutes($expireMinutes),
            ['pdf' => $this->id]
        );
    }

    /**
     * Cek apakah file masih ada
     */
    public function fileExists(): bool
    {
        return $this->file_path && Storage::disk('generated_pdfs')->exists($this->file_path);
    }

    /**
     * Cek apakah PDF sudah expired (auto-cleanup)
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get human-readable file size
     */
    public function getReadableSize(): string
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $i = 0;

        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    // ===== Relationships =====

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
