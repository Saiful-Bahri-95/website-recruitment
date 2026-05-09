<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    protected $table = 'educations';  // ⭐ TAMBAHKAN BARIS INI

    protected $fillable = ['user_id', 'nama_sekolah', 'jurusan', 'tahun_lulus'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
