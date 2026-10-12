USE playground;

-- Tambah kolom uang_diterima dan uang_kembalian untuk pencatatan pembayaran tunai dan cetak struk
ALTER TABLE transaksi 
    ADD COLUMN uang_diterima INT NULL AFTER total_harga, 
    ADD COLUMN uang_kembalian INT NULL AFTER uang_diterima;
