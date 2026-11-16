<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller WEB (halaman /buku dan /buku/{id}).
 *
 * - index : hanya menyiapkan halaman + daftar kategori untuk <select> pada form.
 *           Data buku DIAMBIL OLEH BROWSER lewat Fetch API ke GET /api/buku (Modul 4).
 * - show  : tetap server-side seperti Modul 1/3 (membaca database).
 */
class BukuController extends Controller
{
    private function formatBuku(Buku $buku): array
    {
        return [
            'id'           => $buku->id,
            'judul'        => $buku->judul,
            'penulis'      => $buku->penulis,
            'tahun_terbit' => $buku->tahun_terbit,
            'kategori'     => $buku->kategori?->nama ?? '-',
        ];
    }

    public function index()
    {
        $kategoris   = Kategori::orderBy('nama')->get(['id', 'nama']);
        $daftarJudul = Buku::orderByDesc('id')->limit(10)->get(['id', 'judul']);

        return view('buku.index', compact('kategoris', 'daftarJudul'));
    }

    public function show(string $id)
    {
        $model = ctype_digit($id) ? Buku::with('kategori')->find((int) $id) : null;
        $buku  = $model ? $this->formatBuku($model) : null;

        return response()->view('buku.show', ['buku' => $buku], $buku ? 200 : 404);
    }

    // A2 ditambal: query memakai binding (placeholder ?)
    public function cari(Request $request)
    {
        $judul = $request->query('judul');
        $hasil = DB::select('SELECT * FROM bukus WHERE judul = ?', [$judul]);

        return response()->json($hasil);
    }

    // A3 ditambal: penghapusan lewat DELETE + token CSRF
    public function destroy(string $id)
    {
        $buku = Buku::withCount('peminjamans')->findOrFail($id);

        if ($buku->peminjamans_count > 0) {
            return redirect()->route('buku.index')
                ->with('error', 'Buku tidak dapat dihapus karena masih memiliki riwayat peminjaman.');
        }

        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }

    // Halaman form tambah buku (form ada di halaman daftar buku)
    public function create()
    {
        return $this->index();
    }
}
