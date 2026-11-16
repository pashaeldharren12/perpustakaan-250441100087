<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate pusat untuk pengelolaan buku: hanya admin dan petugas
        Gate::define('kelola-buku', function (User $user) {
            return $user->hasAnyRole(['admin', 'petugas']);
        });
    }
}
