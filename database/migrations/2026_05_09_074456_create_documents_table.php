<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', [
                'pas_foto',
                'surat_lamaran',
                'cv',
                'ktp',
                'npwp',
                'bpjs_kesehatan',
                'vaksin',
                'skck',
                'ijazah',
                'transkrip_nilai',
                'kartu_kuning',
                'paklaring'
            ]);
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
