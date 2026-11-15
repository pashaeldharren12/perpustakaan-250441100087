<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index', [
            'totalBuku'         => Buku::count(),
            'totalKategori'     => Kategori::count(),
            'totalPeminjaman'   => Peminjaman::count(),
            'sedangDipinjam'    => Peminjaman::where('status', 'dipinjam')->count(),
            'sudahDikembalikan' => Peminjaman::where('status', 'dikembalikan')->count(),
            'menungguPersetujuan' => Peminjaman::where('status', 'diajukan')->count(),
            'bukuPerKategori'   => Kategori::withCount('bukus')->orderBy('nama')->get(),
        ]);
    }
}
