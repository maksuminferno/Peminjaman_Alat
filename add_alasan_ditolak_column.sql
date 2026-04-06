-- Add alasan_ditolak column to peminjaman table
-- Run this SQL to add rejection reason field

ALTER TABLE peminjaman 
ADD COLUMN alasan_ditolak TEXT NULL AFTER status;
