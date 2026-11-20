<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// routes/web.php
Route::middleware('auth')->group(function () {

    // Semua role
    Route::middleware('role:super_admin,admin,production')->group(function () {
        Route::get('barang', [BarangController::class, 'index'])->name('barang.index');
        Route::resource('transaksi', TransaksiController::class)
             ->only(['index', 'create', 'store', 'show']);
    });

    // Super admin dan admin
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::resource('kategori', KategoriController::class);
        Route::resource('pemasok', PemasokController::class);
        Route::resource('barang', BarangController::class)->except(['index']);
        Route::get('laporan/stok-menipis', [LaporanController::class, 'stokMenipis']);
        Route::get('laporan/terlaris', [LaporanController::class, 'terlaris']);
    });

    // Super admin saja
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::delete('transaksi/{transaksi}', [TransaksiController::class, 'destroy']);
    });
});

require __DIR__.'/auth.php';
