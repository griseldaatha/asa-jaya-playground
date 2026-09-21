<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'kode_transaksi',
        'access_token',
        'kasir_id',
        'meja_id',
        'nama_pemesan',
        'tipe_pesanan',
        'metode_pembayaran',
        'total_harga',
        'status_pembayaran',
        'status_pesanan',
        'waktu_transaksi'
    ];

    /**
     * Relasi ke User (Sebagai Kasir)
     */
    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id', 'id');
    }

    /**
     * Relasi ke Meja
     */
    public function meja()
    {
        return $this->belongsTo(Meja::class, 'meja_id', 'id');
    }

    /**
     * Relasi ke Detail Transaksi
     */
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id', 'id');
    }
}
