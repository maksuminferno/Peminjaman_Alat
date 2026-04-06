-- Manual SQL script to fix pengembalian table
-- Run this in phpMyAdmin or MySQL command line

-- Add kondisi_alat column if it doesn't exist
ALTER TABLE pengembalian 
ADD COLUMN kondisi_alat VARCHAR(20) DEFAULT 'baik' AFTER denda;

-- Add deskripsi_kerusakan column
ALTER TABLE pengembalian 
ADD COLUMN deskripsi_kerusakan TEXT NULL AFTER kondisi_alat;
