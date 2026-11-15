<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';

    public $timestamps = false;

    /**
     * Mass assignment protection: hanya kolom di bawah ini yang boleh diisi
     * lewat create()/fill()/update(). Kolom "id" (dan kolom lain seperti
     * created_at) sengaja TIDAK dimasukkan sehingga diabaikan.
     */
    protected $fillable = [
        'kategori_id',
        'judul',
        'penulis',
        'tahun_terbit',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }
}
