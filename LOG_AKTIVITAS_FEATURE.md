# Log Aktivitas - Activity Logging

## Aktivitas yang Dicatat

Sistem sekarang mencatat semua aktivitas berikut di **Log Aktivitas**:

### 1. User Management
- ✅ Menambahkan user baru (dengan role)
- ✅ Mengedit user (dengan role baru)
- ✅ Menghapus user

### 2. Equipment (Alat) Management
- ✅ Menambahkan alat baru (dengan stok)
- ✅ Mengedit alat (dengan tambah stok)
- ✅ Menghapus alat

### 3. Category (Kategori) Management
- ✅ Menambahkan kategori baru
- ✅ Mengedit kategori
- ✅ Menghapus kategori

### 4. Borrowing (Peminjaman) Management
- ✅ Menambahkan peminjaman baru
- ✅ Mengedit peminjaman
- ✅ Menghapus peminjaman

### 5. Return (Pengembalian) Management
- ✅ Mengedit pengembalian (dengan kondisi)
- ✅ Menghapus pengembalian

## Format Log

Setiap log aktivitas berisi:
- **User** yang melakukan aksi
- **Aktivitas** yang dilakukan (deskripsi lengkap)
- **Waktu** aktivitas dicatat

## Cara Melihat Log

1. Login sebagai **Admin**
2. Klik menu **Log Aktivitas** di sidebar
3. Lihat semua aktivitas yang tercatat

## Contoh Aktivitas

| User | Aktivitas | Waktu |
|------|-----------|-------|
| admin | Menambahkan user baru: petugas1 (petugas) | 26 Feb 2026 10:30:00 |
| admin | Menambahkan alat baru: Computer (stok: 5) | 26 Feb 2026 11:15:00 |
| admin | Mengedit alat: Projector (Tambah stok: 2) | 26 Feb 2026 14:20:00 |
| admin | Menambahkan peminjaman: John Doe - Computer (1 item) | 26 Feb 2026 15:45:00 |
| admin | Mengedit pengembalian ID: PGB001 (Kondisi: baik) | 26 Feb 2026 16:30:00 |

## File yang Diupdate

1. `app/Helpers/ActivityHelper.php` - Helper function
2. `app/Http/Controllers/AdminController.php` - Log activity calls
3. `composer.json` - Autoload helper file
4. `routes/web.php` - Route for log aktivitas
5. `resources/views/admin/layout.blade.php` - Menu link
