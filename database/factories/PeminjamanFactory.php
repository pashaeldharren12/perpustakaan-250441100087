<?php

namespace Database\Factories;

use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Anggota;
use App\Models\Buku;

/**
 * @extends Factory<Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      $tanggalPinjam = fake()->dateTimeBetween('-2 months', '-1 month');

return [
    'anggota_id' => Anggota::inRandomOrder()->value('id'),
    'buku_id' => Buku::inRandomOrder()->value('id'),
    'tanggal_pinjam' => $tanggalPinjam->format('Y-m-d'),
    'tanggal_kembali' => fake()->boolean(70)
        ? fake()->dateTimeBetween($tanggalPinjam, 'now')->format('Y-m-d')
        : null,
];
    }
}
