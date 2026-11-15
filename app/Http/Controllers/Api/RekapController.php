<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\JsonResponse;

class RekapController extends Controller
{
    /**
     * GET /api/rekap/buku-per-kategori
     * Jumlah buku per kategori (withCount).
     */
    public function bukuPerKategori(): JsonResponse
    {
        $data = Kategori::withCount('bukus')
            ->orderBy('id')
            ->get()
            ->map(fn (Kategori $k) => [
                'kategori_id' => $k->id,
                'kategori'    => $k->nama,
                'jumlah_buku' => $k->bukus_count,
            ]);

        return response()->json([
            'message' => 'Rekap jumlah buku per kategori',
            'data'    => $data,
        ], 200);
    }

    /**
     * GET /api/rekap/peminjaman-per-buku
     * Total peminjaman per buku (withCount), terbanyak lebih dulu.
     */
    public function peminjamanPerBuku(): JsonResponse
    {
        $data = Buku::with('kategori')
            ->withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->orderBy('id')
            ->get()
            ->map(fn (Buku $b) => [
                'buku_id'         => $b->id,
                'judul'           => $b->judul,
                'penulis'         => $b->penulis,
                'kategori'        => $b->kategori?->nama,
                'total_peminjaman' => $b->peminjamans_count,
            ]);

        return response()->json([
            'message' => 'Rekap total peminjaman per buku',
            'data'    => $data,
        ], 200);
    }
}
