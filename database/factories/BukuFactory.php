<?php

namespace Database\Factories;

use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Kategori;

/**
 * @extends Factory<Buku>
 */
class BukuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
    'kategori_id' => Kategori::inRandomOrder()->value('id'),
    'judul' => ucwords(fake()->words(3, true)),
    'penulis' => fake()->name(),
    'tahun_terbit' => fake()->numberBetween(1990, 2025),
];
    }
}
