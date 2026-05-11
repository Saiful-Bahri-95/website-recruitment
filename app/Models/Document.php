<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'file_path',
        'original_name',
        'file_size',
        'mime_type',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'file_size' => 'integer',
    ];

    /**
     * Constants untuk jenis dokumen
     */
    public const TYPES = [
        'pas_foto' => 'Pas Foto (Background Merah)',
        'surat_lamaran' => 'Surat Lamaran Kerja',
        'cv' => 'Curriculum Vitae (CV)',
        'ktp' => 'KTP',
        'npwp' => 'NPWP',
        'bpjs_kesehatan' => 'BPJS Kesehatan',
        'vaksin' => 'Sertifikat Vaksin',
        'skck' => 'SKCK',
        'ijazah' => 'Ijazah',
        'transkrip_nilai' => 'Transkrip Nilai',
        'kartu_kuning' => 'Kartu Kuning (AK-1)',
        'paklaring' => 'Paklaring',
    ];

    /**
     * Auto-set uploaded_at saat document dibuat
     */
    protected static function booted(): void
    {
        static::creating(function (Document $document) {
            if (!$document->uploaded_at) {
                $document->uploaded_at = now();
            }
        });

        // Auto-delete file dari disk saat record dihapus
        static::deleting(function (Document $document) {
            if ($document->file_path && Storage::disk('documents')->exists($document->file_path)) {
                Storage::disk('documents')->delete($document->file_path);
            }
        });
    }

    /**
     * Generate signed URL untuk VIEW file (preview di browser).
     * URL expired dalam X menit (default 5 menit).
     */
    public function getSecureViewUrl(int $expireMinutes = 5): string
    {
        return URL::temporarySignedRoute(
            'documents.view',
            now()->addMinutes($expireMinutes),
            ['document' => $this->id]
        );
    }

    /**
     * Generate signed URL untuk DOWNLOAD file.
     */
    public function getSecureDownloadUrl(int $expireMinutes = 5): string
    {
        return URL::temporarySignedRoute(
            'documents.download',
            now()->addMinutes($expireMinutes),
            ['document' => $this->id]
        );
    }

    /**
     * Helper untuk dapat label dari type.
     */
    public function getTypeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * Cek apakah file masih ada di disk.
     */
    public function fileExists(): bool
    {
        return $this->file_path && Storage::disk('documents')->exists($this->file_path);
    }

    /**
     * Get human-readable file size.
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

    /**
     * Relationship: Document belongs to User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Document has many access logs.
     */
    public function accessLogs(): HasMany
    {
        return $this->hasMany(DocumentAccessLog::class);
    }
}
