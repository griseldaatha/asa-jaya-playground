<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'kategori_produk_id',
        'nama_produk',
        'harga',
        'stok',
        'foto_produk',
        'is_active',
    ];

    /**
     * Relasi ke Kategori Produk
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_produk_id', 'id');
    }

    /**
     * Relasi ke Detail Transaksi
     */
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'produk_id', 'id');
    }
}
