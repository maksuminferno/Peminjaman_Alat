-- Add deskripsi_kerusakan column to pengembalian table
-- Run this SQL script to add damage description field

-- First, add kondisi_alat column if it doesn't exist
ALTER TABLE pengembalian ADD COLUMN IF NOT EXISTS kondisi_alat VARCHAR(20) DEFAULT 'baik' AFTER denda;

-- Then, add deskripsi_kerusakan column
ALTER TABLE pengembalian ADD COLUMN IF NOT EXISTS deskripsi_kerusakan TEXT NULL AFTER kondisi_alat;

