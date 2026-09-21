<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Relasi ke Transaksi (Sebagai Kasir)
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'kasir_id', 'id');
    }
}
