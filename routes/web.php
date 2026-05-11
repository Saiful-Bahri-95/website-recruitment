<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

Route::get('/', function () {
    return view('welcome');
});

// ===== SECURE DOCUMENT ACCESS =====
// Middleware:
// - auth: harus login
// - signed: URL harus valid signature (cegah tamper)
// - throttle: rate limiting 30 req/menit per IP
Route::middleware(['auth', 'signed', 'throttle:30,1'])
    ->prefix('secure/documents')
    ->name('documents.')
    ->group(function () {
        Route::get('/{document}/view', [DocumentController::class, 'view'])
            ->name('view');

        Route::get('/{document}/download', [DocumentController::class, 'download'])
            ->name('download');
    });

Route::middleware(['auth', 'signed', 'throttle:30,1'])->group(function () {
    // ... routes
});
