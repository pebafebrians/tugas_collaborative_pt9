<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Pemasok;
use App\Services\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;

class InventoryTest extends TestCase
{
    // Ini akan mereset database setiap kali test dijalankan
    use RefreshDatabase;

    protected $inventoryService;

    // Setup data dummy sebelum test berjalan
    protected function setUp(): void
    {
        parent::setUp();

        $this->inventoryService = new InventoryService();

        // Buat User (Petugas)
        User::create(['name' => 'Petugas', 'email' => 'petugas@test.com', 'password' => bcrypt('123'), 'role' => 'production']);

        // Buat Kategori & Pemasok
        Kategori::create(['nama_kategori' => 'Sembako']);
        Pemasok::create(['kode_pemasok' => 'SUP-01', 'nama_pemasok' => 'PT Maju']);

        // Buat Barang dengan Stok Awal 10
        Barang::create([
            'kode_barang' => 'TEST-01',
            'nama_barang' => 'Beras Test',
            'id_kategori' => 1,
            'satuan' => 'sak',
            'harga_beli' => 10000,
            'harga_jual' => 12000,
            'stok' => 10,
            'stok_minimum' => 5
        ]);
    }

    /**
     * SKENARIO 1: Transaksi MASUK harus menambah stok
     */
    public function test_transaksi_masuk_menambah_stok()
    {
        $data = [
            'no_transaksi' => 'TRX-IN-001',
            'tanggal' => date('Y-m-d'),
            'jenis' => 'MASUK',
            'id_user' => 1,
            'id_pemasok' => 1,
            'items' => [['id_barang' => 1, 'jumlah' => 5]]
        ];

        $this->inventoryService->prosesTransaksi($data);

        $barang = Barang::find(1);
        // Stok awal 10 + 5 = 15
        $this->assertEquals(15, $barang->stok);
    }

    /**
     * SKENARIO 2: Transaksi KELUAR harus mengurangi stok
     */
    public function test_transaksi_keluar_mengurangi_stok()
    {
        $data = [
            'no_transaksi' => 'TRX-OUT-001',
            'tanggal' => date('Y-m-d'),
            'jenis' => 'KELUAR',
            'id_user' => 1,
            'nama_pembeli' => 'Warga Desa',
            'items' => [['id_barang' => 1, 'jumlah' => 3]]
        ];

        $this->inventoryService->prosesTransaksi($data);

        $barang = Barang::find(1);
        // Stok awal 10 - 3 = 7
        $this->assertEquals(7, $barang->stok);
    }

    /**
     * SKENARIO 3: Transaksi KELUAR gagal jika stok tidak cukup (Aturan Bisnis Slide 2)
     */
    public function test_transaksi_keluar_gagal_jika_stok_kurang()
    {
        // Beri tahu PHPUnit bahwa kita mengharapkan sebuah Exception (Error)
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Stok tidak cukup");

        $data = [
            'no_transaksi' => 'TRX-OUT-002',
            'tanggal' => date('Y-m-d'),
            'jenis' => 'KELUAR',
            'id_user' => 1,
            'nama_pembeli' => 'Warga Desa',
            'items' => [['id_barang' => 1, 'jumlah' => 15]] // Minta 15, stok cuma 10
        ];

        // Ini harus memicu Exception
        $this->inventoryService->prosesTransaksi($data);
    }

    /**
     * SKENARIO 4: Transaksi gagal & Rollback jika barang tidak ditemukan
     */
    public function test_transaksi_gagal_jika_barang_tidak_ada()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $data = [
            'no_transaksi' => 'TRX-IN-002',
            'tanggal' => date('Y-m-d'),
            'jenis' => 'MASUK',
            'id_user' => 1,
            'id_pemasok' => 1,
            'items' => [['id_barang' => 999, 'jumlah' => 5]] // ID 999 tidak ada
        ];

        $this->inventoryService->prosesTransaksi($data);

        // Pastikan tidak ada transaksi yang tersimpan di database (Rollback berhasil)
        $this->assertDatabaseCount('transaksi', 0);
    }

    /**
     * SKENARIO 5: Uji integritas database - Kode barang tidak boleh duplikat
     */
    public function test_kode_barang_tidak_boleh_duplikat()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Mencoba memasukkan barang dengan kode_barang 'TEST-01' lagi (harusnya error karena unique)
        Barang::create([
            'kode_barang' => 'TEST-01',
            'nama_barang' => 'Beras Palsu',
            'id_kategori' => 1,
            'satuan' => 'sak',
            'harga_beli' => 10000,
            'harga_jual' => 12000,
            'stok' => 10,
            'stok_minimum' => 5
        ]);
    }
}
