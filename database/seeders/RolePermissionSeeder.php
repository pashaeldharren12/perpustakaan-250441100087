<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // permission => role pemilik (sesuai tabel soal no. 4)
        $matrix = [
            'akses-dashboard-admin'               => ['admin'],
            'akses-dashboard-petugas'             => ['petugas'],
            'lihat-daftar-buku'                   => ['admin', 'petugas', 'siswa'],
            'kelola-data-buku'                    => ['admin', 'petugas'],
            'kelola-data-pengguna'                => ['admin'],
            'kelola-role-pengguna'                => ['admin'],
            'kelola-peminjaman'                   => ['admin', 'petugas'],
            'kelola-pengembalian'                 => ['admin', 'petugas'],
            'mengajukan-peminjaman-buku'          => ['admin', 'petugas', 'siswa'],
            'memproses-persetujuan-peminjaman'    => ['admin', 'petugas'],
            'mencatat-pengembalian-buku'          => ['admin', 'petugas'],
            'melihat-status-peminjaman-sendiri'   => ['admin', 'petugas', 'siswa'],
            'melihat-riwayat-peminjaman-sendiri'  => ['admin', 'petugas', 'siswa'],
            'lihat-laporan-perpustakaan'          => ['admin', 'petugas'],
            'lihat-profil-sendiri'                => ['admin', 'petugas', 'siswa'],
            'ubah-profil-sendiri'                 => ['admin', 'petugas', 'siswa'],
        ];

        foreach (['admin', 'petugas', 'siswa'] as $r) {
            Role::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
        }

        foreach ($matrix as $perm => $roles) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
            foreach ($roles as $r) {
                Role::findByName($r, 'web')->givePermissionTo($perm);
            }
        }

        $accounts = [
    ['Admin Perpustakaan',   'admin@perpus.test',   'admin',   env('ADMIN_PASSWORD') ?? throw new \RuntimeException('Isi ADMIN_PASSWORD di file .env')],
    ['Petugas Perpustakaan', 'petugas@perpus.test', 'petugas', 'password'],
    ['Siswa Contoh',         'siswa@perpus.test',   'siswa',   'password'],
];
foreach ($accounts as [$name, $email, $role, $pass]) {
    User::updateOrCreate(
        ['email' => $email],
        ['name' => $name, 'password' => Hash::make($pass)]
    )->syncRoles($role);
}
    }
}
