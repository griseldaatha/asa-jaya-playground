USE playground;

-- Alter tabel meja
ALTER TABLE meja 
    DROP COLUMN nomor_meja, 
    ADD COLUMN qr_token VARCHAR(255) UNIQUE AFTER id, 
    ADD COLUMN is_active BOOLEAN DEFAULT TRUE AFTER qr_code_url;

-- Alter tabel transaksi
-- 1. Drop foreign key lama
ALTER TABLE transaksi DROP FOREIGN KEY transaksi_ibfk_1;

-- 2. Rename kolom dan tambah access_token
ALTER TABLE transaksi 
    RENAME COLUMN user_id TO kasir_id,
    ADD COLUMN access_token VARCHAR(255) UNIQUE AFTER kode_transaksi;

-- 3. Tambahkan kembali foreign key untuk kasir_id
ALTER TABLE transaksi 
    ADD CONSTRAINT fk_transaksi_kasir FOREIGN KEY (kasir_id) REFERENCES users (id) ON DELETE SET NULL;

-- 4. Ubah tipe ENUM
ALTER TABLE transaksi 
    MODIFY COLUMN metode_pembayaran ENUM('tunai', 'payment_gateway') DEFAULT NULL,
    MODIFY COLUMN status_pembayaran ENUM('pending', 'lunas', 'batal', 'kadaluarsa') DEFAULT NULL,
    MODIFY COLUMN status_pesanan ENUM('menunggu', 'diproses', 'selesai', 'dibatalkan') DEFAULT NULL;
