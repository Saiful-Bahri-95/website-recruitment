<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Status verifikasi keseluruhan pelamar
            $table->enum('verification_status', [
                'pending',      // Belum diverifikasi admin
                'in_review',    // Sedang ditinjau admin
                'revision',     // Perlu revisi (ada catatan)
                'approved',     // Lolos verifikasi
                'rejected',     // Ditolak
            ])->default('pending')->after('role');

            // Catatan/feedback dari admin (untuk revisi atau penolakan)
            $table->text('admin_notes')->nullable()->after('verification_status');

            // Kapan terakhir admin memverifikasi
            $table->timestamp('verified_at')->nullable()->after('admin_notes');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['verification_status', 'admin_notes', 'verified_at']);
        });
    }
};
