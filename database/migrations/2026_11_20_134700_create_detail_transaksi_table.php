<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
         if (!Schema::hasTable('detail_transaksi')) {
        Schema::create('detail_transaksi', function (Blueprint $table) {
    $table->id('id_detail_transaksi');

    // Relasi ke tabel transaksi utama
    $table->foreignId('id_transaksi')
          ->constrained('transaksi', 'id_transaksi')
          ->cascadeOnDelete(); // Jika transaksi dihapus, detailnya ikut terhapus

    // Relasi ke tabel barang
    $table->foreignId('id_barang')
          ->constrained('barang', 'id_barang')
          ->restrictOnDelete();

    $table->unsignedInteger('jumlah'); // Jumlah barang yang masuk/keluar
    $table->decimal('harga_satuan', 12, 2); // Harga saat transaksi terjadi
    $table->decimal('subtotal', 14, 2); // jumlah * harga_satuan
    $table->timestamps();
});
    }
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
