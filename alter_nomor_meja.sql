USE playground;

-- Tambah kolom nomor_meja pada tabel meja
ALTER TABLE meja ADD COLUMN nomor_meja INT NULL AFTER id;

-- Isi data lama dengan nomor_meja = id
UPDATE meja SET nomor_meja = id WHERE nomor_meja IS NULL;

-- Tambah UNIQUE index pada kolom nomor_meja
ALTER TABLE meja ADD UNIQUE KEY nomor_meja (nomor_meja);
