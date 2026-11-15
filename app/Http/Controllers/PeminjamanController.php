<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    // Semua role: ajukan peminjaman atas nama user yang login
    public function ajukan(string $id)
    {
        $buku = Buku::findOrFail($id);
        $user = auth()->user();

        // Tabel peminjamans mewajibkan anggota_id -> samakan dengan data anggota milik user
        $anggota = Anggota::firstOrCreate(
            ['email' => $user->email],
            ['nama' => $user->name, 'no_telepon' => '-']
        );

        // cegah pengajuan ganda untuk buku yang sama
        $sudahAda = Peminjaman::where('user_id', $user->id)
            ->where('buku_id', $buku->id)
            ->whereIn('status', ['diajukan', 'dipinjam'])
            ->exists();
        if ($sudahAda) {
            return back()->with('error', 'Buku ini sudah Anda ajukan/pinjam.');
        }

        Peminjaman::create([
            'user_id'        => $user->id,
            'anggota_id'     => $anggota->id,
            'buku_id'        => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status'         => 'diajukan',
        ]);

        return redirect()->route('peminjaman.saya')->with('success', 'Pengajuan peminjaman dikirim.');
    }

    // Riwayat SENDIRI: selalu difilter user login (bukan hanya permission)
    public function riwayatSaya()
    {
        $peminjamans = Peminjaman::with('buku')
            ->where('user_id', auth()->id())
            ->orderByDesc('id')
            ->paginate(10);

        return view('peminjaman.saya', compact('peminjamans'));
    }

    // Detail: pemilik ATAU yang berhak mengelola peminjaman
    // ===== CELAH A4 (Modul 6): IDOR, tanpa pemeriksaan pemilik =====
    public function show(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        return view('peminjaman.show', ['peminjaman' => $peminjaman->load('buku', 'user')]);
    }

    // Admin & petugas
    public function index()
    {
        $peminjamans = Peminjaman::with(['buku', 'anggota', 'user'])->orderByDesc('id')->paginate(15);
        return view('peminjaman.index', compact('peminjamans'));
    }

    public function setujui(Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->status === 'diajukan', 422);
        $peminjaman->update(['status' => 'dipinjam', 'tanggal_pinjam' => now()->toDateString()]);
        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function tolak(Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->status === 'diajukan', 422);
        $peminjaman->update(['status' => 'ditolak']);
        return back()->with('success', 'Peminjaman ditolak.');
    }

    public function pengembalian()
    {
        $peminjamans = Peminjaman::with(['buku', 'anggota', 'user'])
            ->whereIn('status', ['dipinjam', 'dikembalikan'])
            ->orderByDesc('id')
            ->paginate(15);

        return view('peminjaman.pengembalian', compact('peminjamans'));
    }

    public function kembalikan(Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->status === 'dipinjam', 422);
        $peminjaman->update(['status' => 'dikembalikan', 'tanggal_kembali' => now()->toDateString()]);
        return back()->with('success', 'Pengembalian dicatat.');
    }
}
