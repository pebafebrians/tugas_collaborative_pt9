<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
protected $primaryKey = 'id_transaksi';
protected $fillable = ['no_transaksi', 'tanggal', 'jenis', 'id_user', 'id_pemasok', 'nama_pembeli', 'total', 'keterangan'];

public function user() {
    return $this->belongsTo(User::class, 'id_user', 'id');
}
public function pemasok() {
    return $this->belongsTo(Pemasok::class, 'id_pemasok', 'id_pemasok');
}
public function detail() {
    return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
}
}
