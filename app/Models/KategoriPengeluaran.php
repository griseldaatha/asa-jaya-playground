<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPengeluaran extends Model
{
    protected $table = 'kategori_pengeluaran';

    protected $fillable = [
        'nama_kategori',
        'deskripsi'
    ];

    /**
     * Relasi ke Pengeluaran
     */
    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'kategori_pengeluaran_id', 'id');
    }
}
