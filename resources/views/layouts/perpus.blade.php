<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') | Perpustakaan Digital</title>
    @vite('resources/css/perpus.css')
    <style>
        .nav-spacer { margin-left: auto; }
        .navbar .container { align-items: center; flex-wrap: wrap; }
        .nav-user { color: #fff; font-size: .9rem; }
        .nav-user small { color: var(--accent); }
        .navbar button.nav-btn { background: transparent; border: 1px solid #fff; color: #fff; padding: .2rem .7rem; border-radius: 6px; cursor: pointer; font: inherit; }
        .flash { padding: .7rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .flash-success { background: #e6f6ec; color: #1b6b3a; border: 1px solid #b7e2c6; }
        .flash-error { background: #fdecea; color: #a12622; border: 1px solid #f5c2be; }
        table.tabel { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .tabel th, .tabel td { padding: .65rem .9rem; text-align: left; border-bottom: 1px solid #eef1f5; }
        .tabel th { background: var(--primary); color: #fff; font-size: .9rem; }
        .inline-form { display: inline; }
        .inline-form button, .tabel button { cursor: pointer; font: inherit; padding: .25rem .6rem; border-radius: 6px; border: 1px solid #cfd6df; background: #fff; }
        .grid-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: .6rem; margin-bottom: 1.2rem; max-width: 760px; }
        .grid-form input, .grid-form select { padding: .45rem .6rem; border: 1px solid #cfd6df; border-radius: 6px; font: inherit; }
        .menu-list li { margin: .35rem 0; }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container">
            <h1 class="brand">📚 Perpustakaan Digital</h1>
            <p class="tagline">Jendela ilmu, dalam genggaman</p>
        </div>
    </header>

    <nav class="navbar">
        <div class="container">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>

            @auth
                @can('akses-dashboard-admin')
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard Admin</a>
                @endcan
                @can('akses-dashboard-petugas')
                    <a href="{{ route('petugas.dashboard') }}" class="{{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">Dashboard Petugas</a>
                @endcan
                @can('lihat-daftar-buku')
                    <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">Daftar Buku</a>
                @endcan
                @can('kelola-peminjaman')
                    <a href="{{ route('peminjaman.index') }}" class="{{ request()->routeIs('peminjaman.index') ? 'active' : '' }}">Peminjaman</a>
                @endcan
                @can('kelola-pengembalian')
                    <a href="{{ route('pengembalian.index') }}" class="{{ request()->routeIs('pengembalian.*') ? 'active' : '' }}">Pengembalian</a>
                @endcan
                @can('lihat-laporan-perpustakaan')
                    <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">Laporan</a>
                @endcan
                @can('melihat-riwayat-peminjaman-sendiri')
                    <a href="{{ route('peminjaman.saya') }}" class="{{ request()->routeIs('peminjaman.saya') ? 'active' : '' }}">Riwayat Saya</a>
                @endcan
                @role('admin')
                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">Kelola Pengguna</a>
                @endrole
                @can('lihat-profil-sendiri')
                    <a href="{{ route('profile.edit') }}">Profil</a>
                @endcan

                <span class="nav-spacer nav-user">{{ auth()->user()->name }}
                    <small>({{ auth()->user()->getRoleNames()->join(', ') }})</small></span>
                <form method="POST" action="{{ route('logout') }}" class="inline-form">
                    @csrf
                    <button type="submit" class="nav-btn">Logout</button>
                </form>
            @else
                <a class="nav-spacer" href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Daftar</a>
            @endauth
        </div>
    </nav>

    <main class="container content">
        @if (session('success')) <div class="flash flash-success">{{ session('success') }}</div> @endif
        @if (session('error'))   <div class="flash flash-error">{{ session('error') }}</div> @endif
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            &copy; {{ date('Y') }} Perpustakaan Digital &mdash; 250441100087
        </div>
    </footer>
</body>
</html>
