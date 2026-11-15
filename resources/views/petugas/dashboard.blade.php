@extends('layouts.perpus')
@section('title', 'Dashboard Petugas')

@section('content')
    <style>
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-top: 1.2rem; }
        .menu-card { display: block; background: #fff; padding: 1.3rem; border-radius: 12px; text-decoration: none; color: var(--text); box-shadow: 0 2px 8px rgba(0,0,0,.08); border-left: 5px solid var(--accent); transition: transform .15s, box-shadow .15s; }
        .menu-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,.12); }
        .menu-card .ikon { font-size: 1.8rem; }
        .menu-card h3 { color: var(--primary); margin: .4rem 0 .2rem; font-size: 1.05rem; }
        .menu-card p { color: var(--muted); font-size: .88rem; }
    </style>

    <h2 class="page-title">Dashboard Petugas</h2>
    <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong>.</p>

    <div class="menu-grid">
        <a href="{{ route('buku.index') }}" class="menu-card">
            <div class="ikon">📚</div>
            <h3>Kelola Data Buku</h3>
            <p>Tambah, ubah, dan hapus buku.</p>
        </a>
        <a href="{{ route('peminjaman.index') }}" class="menu-card">
            <div class="ikon">📝</div>
            <h3>Proses Peminjaman</h3>
            <p>Setujui atau tolak pengajuan.</p>
        </a>
        <a href="{{ route('pengembalian.index') }}" class="menu-card">
            <div class="ikon">↩️</div>
            <h3>Proses Pengembalian</h3>
            <p>Catat buku yang dikembalikan.</p>
        </a>
        <a href="{{ route('laporan.index') }}" class="menu-card">
            <div class="ikon">📊</div>
            <h3>Laporan Perpustakaan</h3>
            <p>Ringkasan data perpustakaan.</p>
        </a>
    </div>
@endsection