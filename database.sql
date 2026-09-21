DROP DATABASE IF EXISTS playground;
CREATE DATABASE playground;
USE playground;

CREATE TABLE `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(100),
    `email` VARCHAR(70) UNIQUE,
    `role` ENUM('owner', 'kasir'),
    `password` VARCHAR(100),
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE `kategori_produk` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama_kategori` VARCHAR(25),
    `deskripsi` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE `meja` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nomor_meja` VARCHAR(20),
    `qr_code_url` VARCHAR(100),
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE `produk` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `kategori_produk_id` BIGINT UNSIGNED NULL,
    `nama_produk` VARCHAR(100),
    `harga` INT,
    `stok` INT,
    `foto_produk` VARCHAR(255) NULL,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`kategori_produk_id`) REFERENCES `kategori_produk`(`id`) ON DELETE SET NULL
);

CREATE TABLE `transaksi` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `kode_transaksi` VARCHAR(255) UNIQUE,
    `user_id` BIGINT UNSIGNED NULL,
    `meja_id` BIGINT UNSIGNED NULL,
    `nama_pemesan` VARCHAR(100),
    `tipe_pesanan` ENUM('qr code', 'kasir'),
    `metode_pembayaran` ENUM('tunai', 'transfer'),
    `total_harga` INT,
    `status_pembayaran` ENUM('pending', 'lunas', 'batal'),
    `status_pesanan` ENUM('menunggu', 'diproses', 'selesai'),
    `waktu_transaksi` DATETIME,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`meja_id`) REFERENCES `meja`(`id`) ON DELETE SET NULL
);

CREATE TABLE `detail_transaksi` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `transaksi_id` BIGINT UNSIGNED,
    `produk_id` BIGINT UNSIGNED,
    `jumlah` INT,
    `harga_satuan` INT,
    `subtotal` INT,
    `catatan` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`produk_id`) REFERENCES `produk`(`id`) ON DELETE CASCADE
);

CREATE TABLE `kategori_pengeluaran` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama_kategori` VARCHAR(25),
    `deskripsi` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE `pengeluaran` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED,
    `kategori_pengeluaran_id` BIGINT UNSIGNED NULL,
    `tanggal_pengeluaran` DATE,
    `nama_pengeluaran` VARCHAR(100),
    `nominal` INT,
    `deskripsi` VARCHAR(255),
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`kategori_pengeluaran_id`) REFERENCES `kategori_pengeluaran`(`id`) ON DELETE SET NULL
);
