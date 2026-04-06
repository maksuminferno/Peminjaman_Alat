-- Add disetujui_oleh column to peminjaman table
-- Run this SQL script to add the approved_by field

ALTER TABLE peminjaman 
ADD COLUMN disetujui_oleh BIGINT UNSIGNED NULL AFTER id_user,
ADD FOREIGN KEY (disetujui_oleh) REFERENCES users(id_user) ON DELETE SET NULL;
