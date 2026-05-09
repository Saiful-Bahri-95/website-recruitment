<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Biodata extends Model
{
    protected $table = 'biodata';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'alamat_ktp',
        'alamat_domisili',
        'tempat_lahir',
        'tanggal_lahir',
        'tinggi_badan',
        'berat_badan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
