# Penambahan Fitur Pelacakan Kondisi Alat

Fitur ini menambahkan kemampuan untuk melacak kondisi alat secara keseluruhan (baik atau rusak) di tabel alat, serta melacak kondisi spesifik saat pengembalian.

## Perubahan yang Dibuat

### 1. Struktur Database
- Menambahkan kolom `kondisi` ke tabel `alat` untuk menyimpan kondisi umum alat ('baik' atau 'rusak')
- Menambahkan kolom `kondisi_alat` ke tabel `pengembalian` untuk menyimpan kondisi umum dari pengembalian
- Membuat tabel baru `detail_pengembalian` untuk melacak kondisi masing-masing alat yang dikembalikan:
  - `id_pengembalian`: Referensi ke tabel pengembalian
  - `id_alat`: Referensi ke tabel alat
  - `jumlah_dikembalikan`: Jumlah alat yang dikembalikan
  - `kondisi_alat`: Kondisi alat saat dikembalikan ('baik' atau 'rusak')

### 2. Model
- Menambahkan model `DetailPengembalian.php`
- Memperbarui relasi di model `Pengembalian.php` dan `Alat.php`
- Menambahkan kolom `kondisi` ke fillable di model `Alat.php`

### 3. Controller
- Memperbarui metode `storeReturn()` di `PeminjamanController.php` untuk:
  - Menerima data kondisi alat dari formulir
  - Membuat catatan rinci tentang kondisi setiap alat yang dikembalikan
  - Memperbarui stok hanya untuk alat yang dikembalikan dalam kondisi baik
  - Memperbarui kondisi alat di tabel alat berdasarkan hasil pengembalian

### 4. View
- Memperbarui formulir pengembalian (`return.blade.php`) untuk:
  - Memungkinkan pengguna menentukan kondisi setiap alat yang dikembalikan
  - Memungkinkan pengguna menentukan jumlah alat yang dikembalikan

## Cara Menggunakan

1. Jalankan migrasi untuk memperbarui struktur database:
   ```bash
   php artisan migrate
   ```
   
   Atau gunakan skrip SQL jika migrasi tidak dapat dijalankan:
   ```sql
   source add_condition_tracking.sql
   ```

2. Saat pengguna mengembalikan alat, mereka sekarang akan diminta untuk menentukan:
   - Jumlah alat yang dikembalikan
   - Kondisi masing-masing alat ('baik' atau 'rusak')

3. Stok alat hanya akan diperbarui untuk alat yang dikembalikan dalam kondisi baik.

4. Kondisi alat di tabel alat akan diperbarui berdasarkan kondisi terakhir saat pengembalian.

## Catatan Penting

- Jika salah satu alat dalam pengembalian memiliki kondisi 'rusak', maka kondisi keseluruhan pengembalian juga akan dicatat sebagai 'rusak'
- Alat yang dikembalikan dalam kondisi 'rusak' tidak akan ditambahkan kembali ke stok yang tersedia
- Kondisi alat di tabel alat akan diperbarui berdasarkan kondisi terakhir saat pengembalian
- Validasi dilakukan untuk memastikan kondisi hanya bisa berupa 'baik' atau 'rusak'