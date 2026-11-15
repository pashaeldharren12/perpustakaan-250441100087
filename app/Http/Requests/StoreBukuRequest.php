<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    /**
     * API ini tidak memakai autentikasi, jadi semua request diizinkan.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'kategori_id'  => ['required', 'integer', 'exists:kategoris,id'],
            'judul'        => ['required', 'string', 'min:3', 'max:150'],
            'penulis'      => ['required', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => ['required', 'integer', 'between:1900,' . now()->year],
        ];
    }

    /**
     * Pesan error kustom (Bahasa Indonesia).
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $tahunIni = now()->year;

        return [
            // pesan umum
            'required' => ':Attribute wajib diisi.',
            'integer'  => ':Attribute harus berupa bilangan bulat.',
            'string'   => ':Attribute harus berupa teks.',

            // kategori_id
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',

            // judul
            'judul.string' => 'Judul buku harus berupa teks.',
            'judul.min'    => 'Judul buku minimal 3 karakter',
            'judul.max'    => 'Judul buku maksimal 150 karakter',

            // penulis
            'penulis.string' => 'Penulis harus berupa teks.',
            'penulis.min'    => 'Nama penulis minimal 3 karakter',
            'penulis.max'    => 'Nama penulis maksimal 100 karakter',

            // tahun_terbit
            'tahun_terbit.between' => "Tahun terbit harus antara 1900 sampai {$tahunIni}",
        ];
    }

    /**
     * Nama atribut yang ramah untuk pesan error.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'kategori_id'  => 'kategori',
            'judul'        => 'judul buku',
            'penulis'      => 'penulis',
            'tahun_terbit' => 'tahun terbit',
        ];
    }
}
