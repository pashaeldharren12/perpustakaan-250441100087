@extends('layouts.perpus')
@section('title', 'Detail Peminjaman')
@section('content')
    <h2 class="page-title">Detail Peminjaman</h2>
    <dl>
        <dt>Peminjam</dt><dd>{{ $peminjaman->user?->name ?? $peminjaman->anggota?->nama ?? '-' }}</dd>
        <dt>Buku</dt><dd>{{ $peminjaman->buku?->judul ?? '-' }}</dd>
        <dt>Status</dt><dd>{{ ucfirst($peminjaman->status) }}</dd>
        <dt>Tanggal Pinjam</dt><dd>{{ $peminjaman->tanggal_pinjam }}</dd>
        <dt>Tanggal Kembali</dt><dd>{{ $peminjaman->tanggal_kembali ?? '-' }}</dd>
    </dl>
    <p><a href="{{ url()->previous() }}">&larr; Kembali</a></p>
@endsection
