-- SQL script to add condition tracking to equipment return functionality

-- Add kondisi_alat column to pengembalian table
ALTER TABLE pengembalian ADD COLUMN kondisi_alat VARCHAR(20) DEFAULT 'baik';

-- Create detail_pengembalian table to track individual equipment return conditions
CREATE TABLE detail_pengembalian (
    id_detail_pengembalian INT AUTO_INCREMENT PRIMARY KEY,
    id_pengembalian BIGINT UNSIGNED NOT NULL,
    id_alat BIGINT UNSIGNED NOT NULL,
    jumlah_dikembalikan INT NOT NULL,
    kondisi_alat VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengembalian) REFERENCES pengembalian(id_pengembalian) ON DELETE CASCADE,
    FOREIGN KEY (id_alat) REFERENCES alat(id_alat) ON DELETE CASCADE
);

-- Update existing records to have 'baik' condition if they don't have one
UPDATE pengembalian SET kondisi_alat = 'baik' WHERE kondisi_alat IS NULL;