<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ===== TEMPORARY: Test Components =====
Route::get('/test-components', function () {
    return view('test-components');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===== PELAMAR ROUTES =====
Route::middleware(['auth', 'verified'])->group(function () {
    // Biodata
    Route::get('/biodata', [\App\Http\Controllers\BiodataController::class, 'edit'])
        ->name('biodata.edit');
    Route::put('/biodata', [\App\Http\Controllers\BiodataController::class, 'update'])
        ->name('biodata.update');

    // Dokumen Pelamar
    Route::get('/documents', [\App\Http\Controllers\DocumentUploadController::class, 'index'])
        ->name('documents.index');
    Route::post('/documents', [\App\Http\Controllers\DocumentUploadController::class, 'store'])
        ->name('documents.upload');
    Route::delete('/documents/{document}', [\App\Http\Controllers\DocumentUploadController::class, 'destroy'])
        ->name('documents.destroy');

    // Pembayaran
    Route::get('/payment', [\App\Http\Controllers\PaymentController::class, 'index'])
        ->name('payment.index');
    Route::post('/payment', [\App\Http\Controllers\PaymentController::class, 'store'])
        ->name('payment.upload');

    Route::get('/status', [App\Http\Controllers\StatusController::class, 'index'])
        ->name('status');
});

// ===== SECURE DOCUMENT ACCESS (Admin via signed URL) =====
Route::middleware(['auth', 'signed', 'throttle:30,1'])
    ->prefix('secure/documents')
    ->group(function () {
        Route::get('/{document}/view', [DocumentController::class, 'view'])
            ->name('documents.view');
        Route::get('/{document}/download', [DocumentController::class, 'download'])
            ->name('documents.download');
    });

// ===== SECURE PDF ACCESS =====
Route::middleware(['auth', 'signed', 'throttle:30,1'])
    ->prefix('secure/pdf')
    ->name('pdf.')
    ->group(function () {
        Route::get('/{pdf}/view', [PdfController::class, 'view'])->name('view');
        Route::get('/{pdf}/download', [PdfController::class, 'download'])->name('download');
    });

// ===== SECURE PAYMENT PROOF ACCESS (Admin via signed URL) =====
Route::middleware(['auth', 'signed', 'throttle:30,1'])
    ->prefix('secure/payment')
    ->group(function () {
        Route::get('/{payment}/proof', [\App\Http\Controllers\PaymentController::class, 'viewProof'])
            ->name('payment.proof.view');
    });

require __DIR__ . '/auth.php';
