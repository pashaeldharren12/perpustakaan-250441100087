<?php

use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
 * MODUL 5: route API dibungkus group 'web' (session + CSRF) + auth + permission,
 * sehingga halaman /buku (Fetch API) memakai sesi login yang sama.
 * - Baca (index, show)           : permission lihat-daftar-buku  (semua role)
 * - Tulis (store, update, destroy): permission kelola-data-buku   (admin, petugas)
 * Nama route api.buku.* dipertahankan.
 */
Route::middleware(['web', 'auth', 'permission:lihat-daftar-buku'])->group(function () {
    Route::apiResource('buku', BukuController::class)->only(['index', 'show'])->names('api.buku');
});

Route::middleware(['web', 'auth', 'permission:kelola-data-buku'])->group(function () {
    Route::apiResource('buku', BukuController::class)->only(['store', 'update', 'destroy'])->names('api.buku');
});

// Rekap = laporan perpustakaan
Route::middleware(['web', 'auth', 'permission:lihat-laporan-perpustakaan'])->group(function () {
    Route::get('/rekap/buku-per-kategori', [RekapController::class, 'bukuPerKategori'])
        ->name('api.rekap.buku-per-kategori');

    Route::get('/rekap/peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku'])
        ->name('api.rekap.peminjaman-per-buku');

    // Alias typo dari soal Modul 4
    Route::get('/rekap/pemminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku'])
        ->name('api.rekap.pemminjaman-per-buku');
});
