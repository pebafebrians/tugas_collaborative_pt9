<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class LaporanService
{
    /**
     * 1. LAPORAN STOK MENIPIS
     * Mengambil daftar barang yang stoknya sama dengan atau kurang dari stok minimum.
     * (Sangat berguna untuk Manajer agar segera melakukan restock).
     */
    public function getStokMenipis()
    {
        return Barang::with('kategori') // Eager loading agar nama kategori ikut tampil
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->where('is_active', true)
            ->orderBy('stok', 'asc') // Urutkan dari yang paling kritis
            ->get();
    }

    /**
     * 2. LAPORAN RIWAYAT TRANSAKSI (Detail)
     * Mengambil semua transaksi beserta siapa petugasnya, pemasoknya, dan barang apa saja yang dibeli.
     * Bisa difilter berdasarkan rentang tanggal.
     */
    public function getRiwayatTransaksi($startDate = null, $endDate = null)
    {
        // Eager loading: user, pemasok, detail, dan barang di dalam detail
        $query = Transaksi::with(['user', 'pemasok', 'detail.barang'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_transaksi', 'desc');

        // Filter jika manajer memasukkan tanggal awal dan akhir
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        return $query->get();
    }

    /**
     * 3. LAPORAN REKAPITULASI TRANSAKSI
     * Menghitung total pemasukan (KELUAR) dan pengeluaran (MASUK) dalam suatu periode.
     */
    public function getRekapTransaksi($startDate = null, $endDate = null)
    {
        $query = Transaksi::select(
            'jenis',
            DB::raw('COUNT(id_transaksi) as total_transaksi'),
            DB::raw('SUM(total) as total_nominal')
        );

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        return $query->groupBy('jenis')->get();
    }

    /**
     * 4. LAPORAN BARANG PALING LARIS (Top 5)
     * Mengambil 5 barang yang paling banyak keluar (terjual) ke masyarakat.
     */
    public function getBarangPalingLaris($limit = 5)
    {
        return DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('barang', 'detail_transaksi.id_barang', '=', 'barang.id_barang')
            ->select(
                'barang.kode_barang',
                'barang.nama_barang',
                DB::raw('SUM(detail_transaksi.jumlah) as total_terjual')
            )
            ->where('transaksi.jenis', 'KELUAR') // Hanya hitung barang yang keluar
            ->groupBy('barang.id_barang', 'barang.kode_barang', 'barang.nama_barang')
            ->orderBy('total_terjual', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * 5. LAPORAN KARTU STOK (Mutasi Barang)
     * Melacak riwayat keluar-masuk untuk SATU barang spesifik.
     */
    public function getKartuStok($id_barang)
    {
        return DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('detail_transaksi.id_barang', $id_barang)
            ->select(
                'transaksi.tanggal',
                'transaksi.no_transaksi',
                'transaksi.jenis',
                'detail_transaksi.jumlah',
                'detail_transaksi.harga_satuan',
                'detail_transaksi.subtotal'
            )
            ->orderBy('transaksi.tanggal', 'asc')
            ->get();
    }
}
