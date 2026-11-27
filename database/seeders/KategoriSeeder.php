<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Sembako', 'keterangan' => 'Sembilan Bahan Pokok Utama'],
            ['nama_kategori' => 'Minuman', 'keterangan' => 'Air mineral, teh, kopi, sirup'],
            ['nama_kategori' => 'Makanan Ringan', 'keterangan' => 'Snack, biskuit, kacang'],
            ['nama_kategori' => 'Alat Tulis', 'keterangan' => 'Buku, pulpen, pensil'],
            ['nama_kategori' => 'Gas & Bahan Bakar', 'keterangan' => 'Tabung gas LPG, minyak tanah'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}
