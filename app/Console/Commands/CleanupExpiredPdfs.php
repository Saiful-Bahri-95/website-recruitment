<?php

namespace App\Console\Commands;

use App\Services\PdfGeneratorService;
use Illuminate\Console\Command;

class CleanupExpiredPdfs extends Command
{
    protected $signature = 'pdfs:cleanup';
    protected $description = 'Hapus PDF yang sudah expired';

    public function handle(PdfGeneratorService $service): int
    {
        $count = $service->cleanupExpired();
        $this->info("Berhasil hapus {$count} PDF expired.");
        return Command::SUCCESS;
    }
}
