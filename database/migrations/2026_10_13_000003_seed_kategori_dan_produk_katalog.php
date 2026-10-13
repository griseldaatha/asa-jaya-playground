<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengisikan data kategori dan produk katalog terbaru agar otomatis tersinkronisasi
     * saat kolaborator menjalankan `php artisan migrate`.
     */
    public function up(): void
    {
        // 1. Data Kategori Produk
        $kategoriList = [
            [
                'id' => 1,
                'nama_kategori' => 'Atribut Bermain',
                'deskripsi' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama_kategori' => 'camilan',
                'deskripsi' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nama_kategori' => 'minuman',
                'deskripsi' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($kategoriList as $kat) {
            DB::table('kategori_produk')->updateOrInsert(
                ['id' => $kat['id']],
                [
                    'nama_kategori' => $kat['nama_kategori'],
                    'deskripsi' => $kat['deskripsi'],
                    'updated_at' => now(),
                ]
            );
        }

        // 2. Data Produk Barang Penjualan
        $produkList = [
            [
                'id' => 1,
                'kategori_produk_id' => 1,
                'nama_produk' => 'Tiket Mandi Bola (1 Jam)',
                'harga' => 10000,
                'stok' => 20,
                'foto_produk' => 'produk/GSTKWZP8tbuagea9YH7x01BKXfwW5nrrAXV9mM6s.jpg',
                'is_active' => 1,
            ],
            [
                'id' => 2,
                'kategori_produk_id' => 1,
                'nama_produk' => 'Kaos Kaki',
                'harga' => 10000,
                'stok' => 30,
                'foto_produk' => 'produk/0XSRNAaZNdZnNFqZqNeC25uGsRORchzCcuhuporc.jpg',
                'is_active' => 1,
            ],
            [
                'id' => 3,
                'kategori_produk_id' => 3,
                'nama_produk' => 'aqua',
                'harga' => 4000,
                'stok' => 15,
                'foto_produk' => 'produk/fCKXxQGilXRr3GefXH9CID3lN7XYtHq28Q5nTrm2.jpg',
                'is_active' => 1,
            ],
            [
                'id' => 4,
                'kategori_produk_id' => 3,
                'nama_produk' => 'teh pucuk',
                'harga' => 3500,
                'stok' => 15,
                'foto_produk' => 'produk/6Ifl9BiSnIVRuDuX1APUOUYAv9vrkhOk8Z0w63Vg.jpg',
                'is_active' => 1,
            ],
            [
                'id' => 5,
                'kategori_produk_id' => 2,
                'nama_produk' => 'oreo',
                'harga' => 2000,
                'stok' => 25,
                'foto_produk' => 'produk/0tFhhzLz1UIdymmf8E6lIlxm7yMUm3vuUD3sskWb.png',
                'is_active' => 1,
            ],
            [
                'id' => 6,
                'kategori_produk_id' => 2,
                'nama_produk' => 'qtela',
                'harga' => 3000,
                'stok' => 10,
                'foto_produk' => 'produk/dm5ABX5lyXia1xVWI4jV3BynkNx0jdF0HyaRXo1o.jpg',
                'is_active' => 1,
            ],
            [
                'id' => 7,
                'kategori_produk_id' => 2,
                'nama_produk' => 'chitato',
                'harga' => 17000,
                'stok' => 20,
                'foto_produk' => 'produk/dEhHVqKTPcVh2MaCpVXiMu4ug6v5AgLne2qbj7BD.jpg',
                'is_active' => 1,
            ],
        ];

        foreach ($produkList as $p) {
            DB::table('produk')->updateOrInsert(
                ['id' => $p['id']],
                [
                    'kategori_produk_id' => $p['kategori_produk_id'],
                    'nama_produk' => $p['nama_produk'],
                    'harga' => $p['harga'],
                    'stok' => $p['stok'],
                    'foto_produk' => $p['foto_produk'],
                    'is_active' => $p['is_active'],
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('produk')->whereIn('id', [1, 2, 3, 4, 5, 6, 7])->delete();
        DB::table('kategori_produk')->whereIn('id', [1, 2, 3])->delete();
    }
};
