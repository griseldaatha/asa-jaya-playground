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
        'uang_diterima',
        'uang_kembalian',
        'midtrans_transaction_id',
        'payment_url',
        'payment_channel',
        'payment_expired_at',
        'status_pembayaran',
        'status_pesanan',
        'waktu_transaksi',
    ];

    protected $appends = [
        'metode_label',
    ];

    /**
     * Accessor terpusat untuk label metode pembayaran
     */
    public function getMetodeLabelAttribute(): string
    {
        if ($this->metode_pembayaran === 'tunai') {
            return 'Tunai';
        }

        $channel = strtolower($this->payment_channel ?? '');

        return match ($channel) {
            'bca_va' => 'Transfer BCA',
            'bni_va' => 'Transfer BNI',
            'bri_va' => 'Transfer BRI',
            'gopay' => 'E-Wallet GoPay',
            'shopeepay' => 'E-Wallet ShopeePay',
            default => 'Non-Tunai',
        };
    }

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

