<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->string('no_transaksi', 30)->unique();          // contoh: TRX-20261120-0001
            $table->date('tanggal');
            $table->enum('jenis', ['MASUK', 'KELUAR']);
            $table->foreignId('id_user')                             // petugas pencatat
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->foreignId('id_pemasok')                          // diisi hanya saat MASUK
                  ->nullable()
                  ->constrained('pemasok', 'id_pemasok')
                  ->nullOnDelete();
            $table->string('nama_pembeli', 120)->nullable();        // diisi saat KELUAR
            $table->decimal('total', 14, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['tanggal', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
