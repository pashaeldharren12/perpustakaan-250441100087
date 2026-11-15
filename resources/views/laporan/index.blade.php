@extends('layouts.perpus')
@section('title', 'Laporan Perpustakaan')
@section('content')
    <h2 class="page-title">Laporan Perpustakaan</h2>
    <table class="tabel" style="max-width:520px">
        <tr><td>Total Buku</td><td>{{ $totalBuku }}</td></tr>
        <tr><td>Total Kategori</td><td>{{ $totalKategori }}</td></tr>
        <tr><td>Total Peminjaman</td><td>{{ $totalPeminjaman }}</td></tr>
        <tr><td>Menunggu Persetujuan</td><td>{{ $menungguPersetujuan }}</td></tr>
        <tr><td>Sedang Dipinjam</td><td>{{ $sedangDipinjam }}</td></tr>
        <tr><td>Sudah Dikembalikan</td><td>{{ $sudahDikembalikan }}</td></tr>
    </table>

    <h3 style="margin:1.2rem 0 .5rem">Buku per Kategori</h3>
    <table class="tabel" style="max-width:520px">
        <thead><tr><th>Kategori</th><th>Jumlah Buku</th></tr></thead>
        <tbody>
        @foreach ($bukuPerKategori as $k)
            <tr><td>{{ $k->nama }}</td><td>{{ $k->bukus_count }}</td></tr>
        @endforeach
        </tbody>
    </table>
@endsection
