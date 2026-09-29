<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';

    protected $fillable = [
        'user_id',
        'kategori_pengeluaran_id',
        'tanggal_pengeluaran',
        'nama_pengeluaran',
        'nominal',
        'deskripsi',
    ];

    /**
     * Relasi ke Kategori Pengeluaran
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriPengeluaran::class, 'kategori_pengeluaran_id', 'id');
    }

    /**
     * Relasi ke User (Yang mencatat pengeluaran)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
