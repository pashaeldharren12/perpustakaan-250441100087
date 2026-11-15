@extends('layouts.perpus')
@section('title', 'Data Peminjaman')
@section('content')
    <h2 class="page-title">Data Peminjaman</h2>
    <table class="tabel">
        <thead><tr><th>Peminjam</th><th>Buku</th><th>Tgl Pinjam</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse ($peminjamans as $p)
            <tr>
                <td>{{ $p->user?->name ?? $p->anggota?->nama ?? '-' }}</td>
                <td>{{ $p->buku?->judul ?? '-' }}</td>
                <td>{{ $p->tanggal_pinjam }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>
                    @can('memproses-persetujuan-peminjaman')
                        @if ($p->status === 'diajukan')
                            <form method="POST" action="{{ route('peminjaman.setujui', $p) }}" class="inline-form">@csrf @method('PATCH')<button>Setujui</button></form>
                            <form method="POST" action="{{ route('peminjaman.tolak', $p) }}" class="inline-form">@csrf @method('PATCH')<button>Tolak</button></form>
                        @endif
                    @endcan
                </td>
            </tr>
        @empty
            <tr><td colspan="5">Belum ada data.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $peminjamans->links() }}
@endsection
