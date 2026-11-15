@extends('layouts.perpus')
@section('title', 'Riwayat Peminjaman Saya')
@section('content')
    <h2 class="page-title">Riwayat Peminjaman Saya</h2>
    <table class="tabel">
        <thead><tr><th>Buku</th><th>Status</th><th>Tgl Pinjam</th><th>Tgl Kembali</th></tr></thead>
        <tbody>
        @forelse ($peminjamans as $p)
            <tr>
                <td><a href="{{ route('peminjaman.show', $p) }}">{{ $p->buku?->judul ?? '-' }}</a></td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>{{ $p->tanggal_pinjam }}</td>
                <td>{{ $p->tanggal_kembali ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="4">Belum ada peminjaman.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $peminjamans->links() }}
@endsection
