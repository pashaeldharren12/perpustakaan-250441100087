<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Mengatur bentuk data buku yang dikirim API.
 *
 * Pemetaan nama atribut respons  <-  kolom/relasi di database:
 *
 *   id                 <-  bukus.id
 *   kategori           <-  kategoris.nama   (lewat relasi, dari bukus.kategori_id)
 *   kategori_id        <-  bukus.kategori_id (dipakai form untuk mengisi <select> saat Ubah)
 *   judul              <-  bukus.judul
 *   penulis            <-  bukus.penulis
 *   tahun              <-  bukus.tahun_terbit   (nama atribut BERBEDA dari kolom)
 *   jumlah_peminjaman  <-  COUNT(peminjamans)   (withCount -> peminjamans_count)
 *   peminjaman         <-  relasi peminjamans (hanya pada detail / show)
 */
class BukuResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'kategori'          => $this->whenLoaded('kategori', fn () => $this->kategori?->nama),
            'kategori_id'       => $this->kategori_id,
            'judul'             => $this->judul,
            'penulis'           => $this->penulis,
            'tahun'             => $this->tahun_terbit,
            'jumlah_peminjaman' => $this->whenCounted('peminjamans'),
            'peminjaman'        => $this->whenLoaded('peminjamans', fn () => $this->peminjamans->map(fn ($p) => [
                'id'              => $p->id,
                'anggota'         => $p->anggota?->nama,
                'tanggal_pinjam'  => $p->tanggal_pinjam,
                'tanggal_kembali' => $p->tanggal_kembali,
            ])),
        ];
    }
}
