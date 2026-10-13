<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use App\Models\Meja;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. User Akun Default
        User::updateOrCreate(
            ['username' => 'owner'],
            [
                'nama' => 'Pak Owner',
                'role' => 'owner',
                'password' => Hash::make('owner123'),
            ]
        );

        User::updateOrCreate(
            ['username' => 'kasir'],
            [
                'nama' => 'Mbak Kasir',
                'role' => 'kasir',
                'password' => Hash::make('kasir123'),
            ]
        );

        // 2. Data Meja
        Meja::updateOrCreate(['nomor_meja' => 1], ['qr_token' => 'X7A9', 'is_active' => true]);
        Meja::updateOrCreate(['nomor_meja' => 2], ['qr_token' => 'KATD0', 'is_active' => true]);
        Meja::updateOrCreate(['nomor_meja' => 3], ['qr_token' => '1FZTB', 'is_active' => true]);

        // 3. Kategori Produk
        $kategori1 = KategoriProduk::updateOrCreate(['id' => 1], ['nama_kategori' => 'Atribut Bermain']);
        $kategori2 = KategoriProduk::updateOrCreate(['id' => 2], ['nama_kategori' => 'camilan']);
        $kategori3 = KategoriProduk::updateOrCreate(['id' => 3], ['nama_kategori' => 'minuman']);

        // 4. Barang Penjualan / Produk
        $produk = [
            [
                'id' => 1,
                'kategori_produk_id' => $kategori1->id,
                'nama_produk' => 'Tiket Mandi Bola (1 Jam)',
                'harga' => 10000,
                'stok' => 20,
                'foto_produk' => 'produk/GSTKWZP8tbuagea9YH7x01BKXfwW5nrrAXV9mM6s.jpg',
                'is_active' => true,
            ],
            [
                'id' => 2,
                'kategori_produk_id' => $kategori1->id,
                'nama_produk' => 'Kaos Kaki',
                'harga' => 10000,
                'stok' => 30,
                'foto_produk' => 'produk/0XSRNAaZNdZnNFqZqNeC25uGsRORchzCcuhuporc.jpg',
                'is_active' => true,
            ],
            [
                'id' => 3,
                'kategori_produk_id' => $kategori3->id,
                'nama_produk' => 'aqua',
                'harga' => 4000,
                'stok' => 15,
                'foto_produk' => 'produk/fCKXxQGilXRr3GefXH9CID3lN7XYtHq28Q5nTrm2.jpg',
                'is_active' => true,
            ],
            [
                'id' => 4,
                'kategori_produk_id' => $kategori3->id,
                'nama_produk' => 'teh pucuk',
                'harga' => 3500,
                'stok' => 15,
                'foto_produk' => 'produk/6Ifl9BiSnIVRuDuX1APUOUYAv9vrkhOk8Z0w63Vg.jpg',
                'is_active' => true,
            ],
            [
                'id' => 5,
                'kategori_produk_id' => $kategori2->id,
                'nama_produk' => 'oreo',
                'harga' => 2000,
                'stok' => 25,
                'foto_produk' => 'produk/0tFhhzLz1UIdymmf8E6lIlxm7yMUm3vuUD3sskWb.png',
                'is_active' => true,
            ],
            [
                'id' => 6,
                'kategori_produk_id' => $kategori2->id,
                'nama_produk' => 'qtela',
                'harga' => 3000,
                'stok' => 10,
                'foto_produk' => 'produk/dm5ABX5lyXia1xVWI4jV3BynkNx0jdF0HyaRXo1o.jpg',
                'is_active' => true,
            ],
            [
                'id' => 7,
                'kategori_produk_id' => $kategori2->id,
                'nama_produk' => 'chitato',
                'harga' => 17000,
                'stok' => 20,
                'foto_produk' => 'produk/dEhHVqKTPcVh2MaCpVXiMu4ug6v5AgLne2qbj7BD.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($produk as $item) {
            Produk::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
