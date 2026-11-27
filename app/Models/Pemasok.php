<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit (karena nama tabelnya 'pemasok', bukan 'pemasoks')
    protected $table = 'pemasok';

    // Tentukan primary key kustom
    protected $primaryKey = 'id_pemasok';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'kode_pemasok',
        'nama_pemasok',
        'kontak_person',
        'no_telepon',
        'alamat',
        'is_active',
    ];

    // Casting tipe data agar lebih aman (opsional tapi disarankan)
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke tabel Transaksi.
     * Satu pemasok bisa memiliki banyak transaksi (khususnya transaksi MASUK).
     */
    public function transaksis()
    {
        // Relasi hasMany ke model Transaksi, dengan foreign key 'id_pemasok' dan local key 'id_pemasok'
        return $this->hasMany(Transaksi::class, 'id_pemasok', 'id_pemasok');
    }
}
