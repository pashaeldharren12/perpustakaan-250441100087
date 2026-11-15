@extends('layouts.perpus')

@section('title', $buku ? $buku['judul'] : 'Buku Tidak Ditemukan')

@section('content')
    @if ($buku)
        <article class="detail">
            <h2>{{ $buku['judul'] }}</h2>
            <dl>
                <dt>ID Buku</dt>
                <dd>{{ $buku['id'] }}</dd>

                <dt>Judul</dt>
                <dd>{{ $buku['judul'] }}</dd>

                <dt>Penulis</dt>
                <dd>{{ $buku['penulis'] }}</dd>

                <dt>Tahun Terbit</dt>
                <dd>{{ $buku['tahun_terbit'] }}</dd>

                <dt>Kategori</dt>
                <dd><span class="badge">{{ $buku['kategori'] }}</span></dd>
            </dl>
            @can('mengajukan-peminjaman-buku')
                <form method="POST" action="{{ route('peminjaman.ajukan', $buku['id']) }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn">Ajukan Peminjaman</button>
                </form>
            @endcan
            <a href="{{ route('buku.index') }}" class="btn">&larr; Kembali ke Daftar Buku</a>
        </article>
    @else
        <div class="not-found">
            <h2>Buku Tidak Ditemukan</h2>
            <p>Maaf, buku yang Anda cari tidak tersedia di perpustakaan kami.</p>
            <a href="{{ route('buku.index') }}" class="btn">&larr; Kembali ke Daftar Buku</a>
        </div>
    @endif
@endsection