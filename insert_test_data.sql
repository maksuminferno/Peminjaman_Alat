-- SQL script to insert test users into the database

-- Insert test users
INSERT OR REPLACE INTO users (username, password, nama, email, no_telp, role, created_at, updated_at) VALUES 
('peminjam1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ahmad Fauzi', 'peminjam1@example.com', '081234567890', 'peminjam', datetime('now'), datetime('now')),
('peminjam2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Nurhaliza', 'peminjam2@example.com', '081234567891', 'peminjam', datetime('now'), datetime('now')),
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin System', 'admin@example.com', '081234567899', 'admin', datetime('now'), datetime('now'));
Username atau password salah.