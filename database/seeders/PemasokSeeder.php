<?php

namespace Database\Seeders;

use App\Models\Pemasok;
use Illuminate\Database\Seeder;

class PemasokSeeder extends Seeder
{
    public function run(): void
    {
        $pemasoks = [
            [
                'kode_pemasok' => 'SUP-001',
                'nama_pemasok' => 'PT. Indofood Makmur',
                'kontak_person' => 'Budi Santoso',
                'no_telepon' => '081234567890',
                'alamat' => 'Jl. Industri Raya No. 12, Jakarta',
                'is_active' => true,
            ],
            [
                'kode_pemasok' => 'SUP-002',
                'nama_pemasok' => 'CV. Sumber Rejeki',
                'kontak_person' => 'Siti Aminah',
                'no_telepon' => '085712345678',
                'alamat' => 'Jl. Pasar Baru No. 45, Bandung',
                'is_active' => true,
            ],
            [
                'kode_pemasok' => 'SUP-003',
                'nama_pemasok' => 'PT. Gas Negara Distribusi',
                'kontak_person' => 'Andi Wijaya',
                'no_telepon' => '021-55566677',
                'alamat' => 'Kawasan Industri Pulogadung, Jakarta',
                'is_active' => true,
            ],
        ];

        foreach ($pemasoks as $pemasok) {
            Pemasok::create($pemasok);
        }
    }
}
