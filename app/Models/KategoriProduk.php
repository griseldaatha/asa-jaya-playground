<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    protected $table = 'kategori_produk';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_produk_id', 'id');
    }
}
