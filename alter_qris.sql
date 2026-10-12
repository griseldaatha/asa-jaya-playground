USE playground;

-- Tambah kolom untuk integrasi QRIS Midtrans
ALTER TABLE transaksi 
    ADD COLUMN midtrans_transaction_id VARCHAR(64) NULL AFTER uang_kembalian,
    ADD COLUMN qris_url VARCHAR(255) NULL AFTER midtrans_transaction_id,
    ADD COLUMN qris_expired_at DATETIME NULL AFTER qris_url;
