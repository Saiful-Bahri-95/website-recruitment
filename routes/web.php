<?php

use App\Http\Controllers\ProfileController;
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
});

require __DIR__ . '/auth.php';
