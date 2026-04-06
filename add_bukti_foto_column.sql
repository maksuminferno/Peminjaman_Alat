-- Tambah kolom bukti_foto di tabel pengembalian
ALTER TABLE pengembalian 
ADD COLUMN bukti_foto VARCHAR(255) NULL AFTER kondisi_alat;
