<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    protected $table = 'meja';

    protected $fillable = [
        'nomor_meja',
        'qr_token',
        'qr_code_url',
        'is_active',
    ];

    /**
     * Relasi ke Transaksi
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'meja_id', 'id');
    }
}
