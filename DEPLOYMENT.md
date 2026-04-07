# Panduan Deployment ke Laravel Cloud

## Prasyarat
1. Akun Laravel Cloud (https://cloud.laravel.com)
2. Laravel CLI terinstall (`composer global require laravel/installer`)
3. Git terinstall
4. Composer terinstall

## Langkah-Langkah Deployment

### 1. Persiapan Project

#### A. Generate APP_KEY
```bash
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe artisan key:generate
```

#### B. Pastikan .env sudah benar
File `.env.production` sudah disiapkan dengan konfigurasi PostgreSQL:
- Host: `ep-old-butterfly-a1sijrn0.aws-ap-southeast-1.pg.laravel.cloud`
- Port: `5432`
- Database: `laravel`
- Username: `laravel`
- Password: `npg_TGOaFrI1BXu2`

### 2. Setup Git

```bash
# Inisialisasi git jika belum ada
git init

# Tambahkan semua file
git add .

# Commit
git commit -m "Initial commit - Sistem Peminjaman Alat"

# Tambahkan remote repository
git remote add origin <YOUR_GIT_URL>
git push -u origin main
```

### 3. Deploy ke Laravel Cloud

#### Option A: Via Laravel Cloud Dashboard
1. Login ke https://cloud.laravel.com
2. Klik "Create Project"
3. Hubungkan dengan repository Git
4. Pilih region: **Asia Pacific (Singapore)** - terdekat dengan Indonesia
5. Configure environment variables dari file `.env.production`

#### Option B: Via Laravel Cloud CLI
```bash
# Install Laravel Cloud CLI
composer global require laravel/cloud-cli

# Login
laravel cloud login

# Deploy
laravel cloud deploy
```

### 4. Konfigurasi Environment Variables di Laravel Cloud

Masukkan variabel environment berikut di dashboard Laravel Cloud:

```
APP_NAME="Sistem Peminjaman Alat"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-url.laravel.cloud

DB_CONNECTION=pgsql
DB_HOST=ep-old-butterfly-a1sijrn0.aws-ap-southeast-1.pg.laravel.cloud
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=npg_TGOaFrI1BXu2
DB_SSLMODE=prefer
```

### 5. Jalankan Migration

Setelah deploy, jalankan migration untuk setup database:

```bash
# Via Laravel Cloud Dashboard
# Buka Console > Run Command

# Atau via CLI
laravel cloud command "php artisan migrate --force"
```

### 6. Seed Database (Optional)

Jika ada seeder untuk data awal:

```bash
laravel cloud command "php artisan db:seed --force"
```

### 7. Setup Storage Link

```bash
laravel cloud command "php artisan storage:link"
```

### 8. Clear Cache

```bash
laravel cloud command "php artisan config:clear"
laravel cloud command "php artisan cache:clear"
laravel cloud command "php artisan route:clear"
laravel cloud command "php artisan view:clear"
```

### 9. Optimize untuk Production

```bash
laravel cloud command "php artisan config:cache"
laravel cloud command "php artisan route:cache"
laravel cloud command "php artisan view:cache"
```

## Struktur Middleware

Project ini sudah dilengkapi middleware role-based:

- **admin** → Akses penuh ke semua fitur admin
- **peminjam** → Akses untuk meminjam dan mengembalikan alat
- **petugas** → Akses untuk approve/reject peminjaman

Role yang valid:
- Admin: `admin`, `administrator`, `superadmin`
- Peminjam: `peminjam`, `borrower`, `user`, `pengguna`
- Petugas: `petugas`, `officer`, `staff`

## Troubleshooting

### Error: Database Connection
1. Pastikan DB_SSLMODE=prefer
2. Cek firewall di sisi database
3. Pastikan IP Laravel Cloud di-whitelist

### Error: 403 Forbidden
1. Cek role user di database
2. Pastikan role sesuai dengan yang diharapkan
3. Cek log di `storage/logs/laravel.log`

### Error: 500 Internal Server Error
```bash
# Cek log
laravel cloud logs

# Atau via dashboard
# https://cloud.laravel.com > Your Project > Logs
```

## Cron Jobs (Jika diperlukan)

Untuk scheduled tasks:

```bash
# Tambahkan ke crontab Laravel Cloud
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

## Monitoring

Setelah deploy, monitor aplikasi di:
- https://cloud.laravel.com > Your Project > Metrics
- Cek error rate, response time, dan resource usage

## Backup Database

Untuk backup database secara berkala:
```bash
# Export database
pg_dump -h ep-old-butterfly-a1sijrn0.aws-ap-southeast-1.pg.laravel.cloud -U laravel laravel > backup_$(date +%Y%m%d).sql
```

## Support

Jika ada masalah:
1. Cek log di `storage/logs/laravel.log`
2. Laravel Cloud Dashboard > Logs
3. Dokumentasi Laravel Cloud: https://cloud.laravel.com/docs

---

**Last Updated**: 2026-04-07
