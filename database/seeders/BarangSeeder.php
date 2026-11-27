<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [
            // Kategori 1: Sembako
            [
                'kode_barang' => 'SBK-001',
                'nama_barang' => 'Beras Premium 5kg',
                'id_kategori' => 1,
                'satuan' => 'sak',
                'harga_beli' => 60000,
                'harga_jual' => 68000,
                'stok' => 50,
                'stok_minimum' => 10,
                'is_active' => true,
            ],
            [
                'kode_barang' => 'SBK-002',
                'nama_barang' => 'Minyak Goreng 2L',
                'id_kategori' => 1,
                'satuan' => 'pcs',
                'harga_beli' => 32000,
                'harga_jual' => 36000,
                'stok' => 8, // Simulasi stok menipis (di bawah minimum 15)
                'stok_minimum' => 15,
                'is_active' => true,
            ],
            [
                'kode_barang' => 'SBK-003',
                'nama_barang' => 'Gula Pasir 1kg',
                'id_kategori' => 1,
                'satuan' => 'kg',
                'harga_beli' => 14000,
                'harga_jual' => 16000,
                'stok' => 30,
                'stok_minimum' => 10,
                'is_active' => true,
            ],

            // Kategori 2: Minuman
            [
                'kode_barang' => 'MNM-001',
                'nama_barang' => 'Air Mineral Galon 19L',
                'id_kategori' => 2,
                'satuan' => 'galon',
                'harga_beli' => 18000,
                'harga_jual' => 22000,
                'stok' => 20,
                'stok_minimum' => 5,
                'is_active' => true,
            ],
            [
                'kode_barang' => 'MNM-002',
                'nama_barang' => 'Teh Botol 350ml',
                'id_kategori' => 2,
                'satuan' => 'pcs',
                'harga_beli' => 3000,
                'harga_jual' => 4000,
                'stok' => 3, // Simulasi stok sangat menipis
                'stok_minimum' => 24,
                'is_active' => true,
            ],

            // Kategori 5: Gas & Bahan Bakar
            [
                'kode_barang' => 'GAS-001',
                'nama_barang' => 'Tabung Gas LPG 3kg',
                'id_kategori' => 5,
                'satuan' => 'tabung',
                'harga_beli' => 20000,
                'harga_jual' => 25000,
                'stok' => 40,
                'stok_minimum' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
