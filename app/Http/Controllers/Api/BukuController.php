<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    private const PER_PAGE = 10;

    /**
     * GET /api/buku
     * Query: cari, kategori, tahun, page
     */
    public function index(Request $request): JsonResponse
    {
        $query = Buku::query()
            ->with('kategori')                 // eager loading relasi kategori
            ->withCount('peminjamans');        // jumlah peminjaman per buku -> peminjamans_count

        // cari: judul ATAU penulis (LIKE)
        if ($request->filled('cari')) {
            $cari = $request->query('cari');

            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        // filter kategori_id
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->query('kategori'));
        }

        // filter tahun_terbit
        if ($request->filled('tahun')) {
            $query->where('tahun_terbit', $request->query('tahun'));
        }

        // data terbaru lebih dulu (tabel bukus tidak punya created_at -> pakai id terbesar)
        $bukus = $query->orderByDesc('id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();               // query string ikut terbawa di link halaman

        return response()->json([
            'message' => 'Daftar buku berhasil diambil',
            'data'    => $bukus->items(),
            'meta'    => [
                'current_page' => $bukus->currentPage(),
                'per_page'     => $bukus->perPage(),
                'total'        => $bukus->total(),
                'last_page'    => $bukus->lastPage(),
                'from'         => $bukus->firstItem(),
                'to'           => $bukus->lastItem(),
            ],
            'links'   => [
                'first' => $bukus->url(1),
                'last'  => $bukus->url($bukus->lastPage()),
                'prev'  => $bukus->previousPageUrl(),
                'next'  => $bukus->nextPageUrl(),
            ],
        ], 200);
    }

    /**
     * POST /api/buku
     */
    public function store(StoreBukuRequest $request): JsonResponse
    {
        // validated() -> hanya field yang lolos validasi, bukan $request->all()
        $buku = Buku::create($request->validated());

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data'    => $buku->load('kategori'),
        ], 201);
    }

    /**
     * GET /api/buku/{id}
     */
    public function show(string $id): JsonResponse
    {
        $buku = Buku::with(['kategori', 'peminjamans.anggota'])
            ->withCount('peminjamans')
            ->find($id);

        if (! $buku) {
            return $this->notFound();
        }

        return response()->json([
            'message' => 'Detail buku berhasil diambil',
            'data'    => $buku,
        ], 200);
    }

    /**
     * PUT /api/buku/{id}
     */
    public function update(UpdateBukuRequest $request, string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (! $buku) {
            return $this->notFound();
        }

        $buku->update($request->validated());

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data'    => $buku->fresh('kategori'),
        ], 200);
    }

    /**
     * DELETE /api/buku/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        $buku = Buku::withCount('peminjamans')->find($id);

        if (! $buku) {
            return $this->notFound();
        }

        // FK peminjamans.buku_id -> bukus.id tanpa cascade: tolak dengan pesan jelas
        // supaya tidak berakhir sebagai error 500 dari database.
        if ($buku->peminjamans_count > 0) {
            return response()->json([
                'message' => 'Buku tidak dapat dihapus karena masih memiliki riwayat peminjaman',
            ], 409);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus',
        ], 200);
    }

    private function notFound(): JsonResponse
    {
        return response()->json(['message' => 'not found'], 404);
    }
}
