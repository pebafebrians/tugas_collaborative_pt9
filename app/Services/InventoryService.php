<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;
use Exception;

class InventoryService
{
    /**
     * Memproses transaksi masuk atau keluar.
     *
     * @param array $data
     * @return Transaksi
     * @throws Exception
     */
    public function prosesTransaksi(array $data)
    {
        // Menggunakan DB::transaction agar jika ada 1 error, semua data di-rollback (batal)
        return DB::transaction(function () use ($data) {

            // 1. Buat Header Transaksi
            $transaksi = Transaksi::create([
                'no_transaksi' => $data['no_transaksi'],
                'tanggal' => $data['tanggal'],
                'jenis' => $data['jenis'],
                'id_user' => $data['id_user'],
                'id_pemasok' => $data['id_pemasok'] ?? null,
                'nama_pembeli' => $data['nama_pembeli'] ?? null,
                'keterangan' => $data['keterangan'] ?? null,
                'total' => 0 // Akan diupdate di akhir setelah loop
            ]);

            $totalTransaksi = 0;

            // 2. Loop Barang yang di-transaksikan
            foreach ($data['items'] as $item) {
                $barang = Barang::findOrFail($item['id_barang']);

                // VALIDASI ATURAN BISNIS: Stok tidak boleh minus
                if ($data['jenis'] === 'KELUAR' && $barang->stok < $item['jumlah']) {
                    throw new Exception("Stok tidak cukup untuk barang: {$barang->nama_barang}. Sisa stok: {$barang->stok}");
                }

                // Tentukan harga (jika MASUK pakai harga_beli, jika KELUAR pakai harga_jual)
                $harga = ($data['jenis'] === 'MASUK') ? $barang->harga_beli : $barang->harga_jual;
                $subtotal = $item['jumlah'] * $harga;
                $totalTransaksi += $subtotal;

                // Simpan ke tabel detail_transaksi
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_barang' => $barang->id_barang,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $harga,
                    'subtotal' => $subtotal,
                ]);

                // 3. Update Stok di Master Barang
                if ($data['jenis'] === 'MASUK') {
                    $barang->increment('stok', $item['jumlah']);
                } else {
                    $barang->decrement('stok', $item['jumlah']);
                }
            }

            // 4. Update total di Header Transaksi
            $transaksi->update(['total' => $totalTransaksi]);

            return $transaksi;
        });
    }
}
