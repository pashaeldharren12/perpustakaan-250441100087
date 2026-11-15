@extends('layouts.perpus')

@section('title', 'Beranda')

@section('content')
    <section class="hero">
        <h2>Selamat Datang di Perpustakaan Digital</h2>
        <p>Temukan berbagai koleksi buku favorit Anda. Jelajahi novel, sejarah, teknologi, dan banyak lagi.</p>
        <a href="{{ route('buku.index') }}" class="btn">Lihat Daftar Buku</a>
    </section>
@endsection