<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('beranda');
})->name('home');

// Pengalih dashboard sesuai role (tujuan setelah login)
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->can('akses-dashboard-admin'))   return redirect()->route('admin.dashboard');
    if ($user->can('akses-dashboard-petugas')) return redirect()->route('petugas.dashboard');
    return redirect()->route('buku.index');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {

    // Modul 6: pencarian buku (A2 sudah memakai binding)
    Route::get('/buku-cari', [BukuController::class, 'cari'])->name('buku.cari');

    // Modul 6: tambah dan hapus buku dijaga Gate kelola-buku (harus sebelum /buku/{id})
    Route::middleware('can:kelola-buku')->group(function () {
        Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
        Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');
    });

    // Dashboard
    Route::get('/admin/dashboard', fn () => view('admin.dashboard'))
        ->middleware('permission:akses-dashboard-admin')->name('admin.dashboard');
    Route::get('/petugas/dashboard', fn () => view('petugas.dashboard'))
        ->middleware('permission:akses-dashboard-petugas')->name('petugas.dashboard');

    // Buku: lihat daftar & detail (semua role)
    Route::middleware('permission:lihat-daftar-buku')->group(function () {
        Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
        Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
    });

    // Peminjaman milik sendiri (semua role)
    Route::post('/buku/{id}/pinjam', [PeminjamanController::class, 'ajukan'])
        ->middleware('permission:mengajukan-peminjaman-buku')->name('peminjaman.ajukan');
    Route::get('/riwayat-saya', [PeminjamanController::class, 'riwayatSaya'])
        ->middleware('permission:melihat-riwayat-peminjaman-sendiri')->name('peminjaman.saya');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])
        ->middleware('permission:melihat-status-peminjaman-sendiri')->name('peminjaman.show');

    // Peminjaman & pengembalian (admin + petugas)
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
        ->middleware('permission:kelola-peminjaman')->name('peminjaman.index');
    Route::patch('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])
        ->middleware('permission:memproses-persetujuan-peminjaman')->name('peminjaman.setujui');
    Route::patch('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])
        ->middleware('permission:memproses-persetujuan-peminjaman')->name('peminjaman.tolak');
    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalian'])
        ->middleware('permission:kelola-pengembalian')->name('pengembalian.index');
    Route::patch('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'kembalikan'])
        ->middleware('permission:mencatat-pengembalian-buku')->name('peminjaman.kembali');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->middleware('permission:lihat-laporan-perpustakaan')->name('laporan.index');

    // Pengguna & role (HANYA admin)
    Route::middleware('permission:kelola-data-pengguna')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])
        ->middleware('permission:kelola-role-pengguna')->name('users.role');

    // Profil sendiri (Breeze memakai $request->user() -> otomatis milik sendiri)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat-profil-sendiri')->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:ubah-profil-sendiri')->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->middleware('permission:ubah-profil-sendiri')->name('profile.destroy');

    Route::get('/uji-error', fn () => throw new \RuntimeException('Uji error NIM 250441100087'));
});

require __DIR__.'/auth.php';
