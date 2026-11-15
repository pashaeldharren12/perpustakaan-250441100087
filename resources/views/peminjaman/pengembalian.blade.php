@extends('layouts.perpus')
@section('title', 'Data Pengembalian')
@section('content')
    <h2 class="page-title">Data Pengembalian</h2>
    <table class="tabel">
        <thead><tr><th>Peminjam</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse ($peminjamans as $p)
            <tr>
                <td>{{ $p->user?->name ?? $p->anggota?->nama ?? '-' }}</td>
                <td>{{ $p->buku?->judul ?? '-' }}</td>
                <td>{{ $p->tanggal_pinjam }}</td>
                <td>{{ $p->tanggal_kembali ?? '-' }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>
                    @can('mencatat-pengembalian-buku')
                        @if ($p->status === 'dipinjam')
                            <form method="POST" action="{{ route('peminjaman.kembali', $p) }}" class="inline-form">@csrf @method('PATCH')<button>Catat Pengembalian</button></form>
                        @endif
                    @endcan
                </td>
            </tr>
        @empty
            <tr><td colspan="6">Belum ada data.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $peminjamans->links() }}
@endsection
