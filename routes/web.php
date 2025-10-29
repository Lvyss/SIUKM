<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UkmController;
use App\Http\Controllers\UkmAdminController;

// halaman utama
Route::get('/', [UkmController::class, 'index'])->name('ukm.index');
Route::get('/ukm/{ukm}', [UkmController::class, 'show'])->name('ukm.show');

// Halaman admin sederhana (tanpa login)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('ukm', UkmAdminController::class);
});