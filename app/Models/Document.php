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
     * Constants untuk jenis dokumen (label untuk display).
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
     * Dokumen WAJIB upload (9 dari 12).
     */
    public const REQUIRED_TYPES = [
        'pas_foto',
        'surat_lamaran',
        'cv',
        'ktp',
        'vaksin',
        'skck',
        'ijazah',
        'transkrip_nilai',
        'kartu_kuning',
    ];

    /**
     * Dokumen OPSIONAL (3 dari 12).
     */
    public const OPTIONAL_TYPES = [
        'npwp',
        'bpjs_kesehatan',
        'paklaring',
    ];

    /**
     * Hint/keterangan per dokumen untuk pelamar.
     */
    public const HINTS = [
        'pas_foto' => 'Background merah, ukuran 4×6 cm, format JPG/PNG',
        'surat_lamaran' => 'Tulis tangan atau ketik, tanda tangan basah',
        'cv' => 'Daftar Riwayat Hidup format PDF',
        'ktp' => 'Foto/scan KTP yang masih berlaku',
        'npwp' => 'Nomor Pokok Wajib Pajak pribadi',
        'bpjs_kesehatan' => 'Kartu peserta BPJS Kesehatan',
        'vaksin' => 'Sertifikat vaksin (gabungkan vaksin 1, 2, booster dalam 1 PDF)',
        'skck' => 'Surat Keterangan Catatan Kepolisian, masih berlaku',
        'ijazah' => 'Scan ijazah pendidikan terakhir',
        'transkrip_nilai' => 'Transkrip nilai pendidikan terakhir',
        'kartu_kuning' => 'Kartu pencari kerja dari Disnaker',
        'paklaring' => 'Surat keterangan kerja dari perusahaan sebelumnya',
    ];

    /**
     * Auto-set uploaded_at saat document dibuat.
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
     *
     * Bisa dipanggil dengan atau tanpa parameter:
     * - $doc->getTypeLabel()       → pakai $this->type
     * - $doc->getTypeLabel('ktp')  → pakai param explicit
     */
    public function getTypeLabel(?string $type = null): string
    {
        $type = $type ?? $this->type;

        return self::TYPES[$type] ?? ($type ?: '');
    }

    /**
     * Helper: dapat hint/keterangan per dokumen.
     */
    public function getHint(?string $type = null): string
    {
        $type = $type ?? $this->type;

        return self::HINTS[$type] ?? '';
    }

    /**
     * Static helper: cek apakah jenis dokumen wajib.
     */
    public static function isRequired(string $type): bool
    {
        return in_array($type, self::REQUIRED_TYPES, true);
    }

    /**
     * Static helper: cek apakah jenis dokumen opsional.
     */
    public static function isOptional(string $type): bool
    {
        return in_array($type, self::OPTIONAL_TYPES, true);
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
     * Alias untuk getReadableSize() (konsistensi naming dengan controller pelamar).
     */
    public function getFormattedSize(): string
    {
        return $this->getReadableSize();
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
