<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('transaksi')) {
            throw new \RuntimeException('Tabel transaksi belum ada. Impor database_playground.sql terlebih dahulu, lalu jalankan php artisan migrate lagi.');
        }

        // 1. payment_url
        if (! Schema::hasColumn('transaksi', 'payment_url')) {
            if (Schema::hasColumn('transaksi', 'va_number')) {
                DB::statement("ALTER TABLE `transaksi` CHANGE COLUMN `va_number` `payment_url` VARCHAR(255) NULL");
                DB::table('transaksi')
                    ->whereNotNull('payment_url')
                    ->where('payment_url', 'not like', '%/snap/%')
                    ->update(['payment_url' => null]);
            } elseif (Schema::hasColumn('transaksi', 'qris_url')) {
                DB::statement("ALTER TABLE `transaksi` CHANGE COLUMN `qris_url` `payment_url` VARCHAR(255) NULL");
                DB::table('transaksi')
                    ->whereNotNull('payment_url')
                    ->where('payment_url', 'not like', '%/snap/%')
                    ->update(['payment_url' => null]);
            } else {
                Schema::table('transaksi', function (Blueprint $table) {
                    $table->string('payment_url', 255)->nullable();
                });
            }
        } else {
            // Kondisi campuran: payment_url sudah ada, jangan drop kolom lama, catat log
            if (Schema::hasColumn('transaksi', 'va_number')) {
                Log::info('Kolom payment_url sudah ada, kolom va_number tetap dipertahankan.');
            }
            if (Schema::hasColumn('transaksi', 'qris_url')) {
                Log::info('Kolom payment_url sudah ada, kolom qris_url tetap dipertahankan.');
            }
        }

        // 2. payment_channel
        if (! Schema::hasColumn('transaksi', 'payment_channel')) {
            if (Schema::hasColumn('transaksi', 'va_bank')) {
                DB::statement("ALTER TABLE `transaksi` CHANGE COLUMN `va_bank` `payment_channel` VARCHAR(30) NULL");
                DB::table('transaksi')->where('payment_channel', 'bca')->update(['payment_channel' => 'bca_va']);
                DB::table('transaksi')->where('payment_channel', 'bni')->update(['payment_channel' => 'bni_va']);
                DB::table('transaksi')->where('payment_channel', 'bri')->update(['payment_channel' => 'bri_va']);
            } else {
                Schema::table('transaksi', function (Blueprint $table) {
                    $table->string('payment_channel', 30)->nullable();
                });
            }
        } else {
            if (Schema::hasColumn('transaksi', 'va_bank')) {
                Log::info('Kolom payment_channel sudah ada, kolom va_bank tetap dipertahankan.');
            }
        }

        // 3. payment_expired_at
        if (! Schema::hasColumn('transaksi', 'payment_expired_at')) {
            if (Schema::hasColumn('transaksi', 'qris_expired_at')) {
                DB::statement("ALTER TABLE `transaksi` CHANGE COLUMN `qris_expired_at` `payment_expired_at` DATETIME NULL");
            } else {
                Schema::table('transaksi', function (Blueprint $table) {
                    $table->dateTime('payment_expired_at')->nullable();
                });
            }
        } else {
            if (Schema::hasColumn('transaksi', 'qris_expired_at')) {
                Log::info('Kolom payment_expired_at sudah ada, kolom qris_expired_at tetap dipertahankan.');
            }
        }

        // 4. midtrans_transaction_id
        if (! Schema::hasColumn('transaksi', 'midtrans_transaction_id')) {
            Schema::table('transaksi', function (Blueprint $table) {
                $table->string('midtrans_transaction_id', 64)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Migration ini sengaja dibuat idempotent tanpa menghapus kolom atau data
        // untuk menjaga integritas data riwayat transaksi dan mencegah kehilangan data.
    }
};

