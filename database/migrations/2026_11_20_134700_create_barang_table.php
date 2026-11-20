<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id('id_barang');
            $table->string('kode_barang', 20)->unique();          // contoh: SBK-001
            $table->string('nama_barang', 120);
            $table->foreignId('id_kategori')
                  ->constrained('kategori', 'id_kategori')
                  ->restrictOnDelete();
            $table->string('satuan', 20);                          // kg, liter, sak, tabung, pcs
            $table->decimal('harga_beli', 12, 2)->default(0);
            $table->decimal('harga_jual', 12, 2)->default(0);
            $table->unsignedInteger('stok')->default(0);           // tidak bisa negatif
            $table->unsignedInteger('stok_minimum')->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('nama_barang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};