<?php

namespace App\Providers;

use App\Models\Peminjaman;
use App\Models\User;
use App\Policies\PeminjamanPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Di Vercel, HTTPS diterima di lapisan depan;
        // paksa URL menggunakan HTTPS saat production.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Gate untuk pengelolaan buku:
        // hanya admin dan petugas.
        Gate::define('kelola-buku', function (User $user) {
            return $user->hasAnyRole(['admin', 'petugas']);
        });

        // Policy pemilik data peminjaman
        // untuk mencegah akses data milik siswa lain.
        Gate::policy(Peminjaman::class, PeminjamanPolicy::class);
    }
}
