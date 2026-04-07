# Panduan Troubleshooting Deployment Laravel Cloud

## ❌ Error: "Deployment failed - unable to determine the cause"

### Solusi Step-by-Step:

---

## STEP 1: Cek Deployment Logs

1. **Buka Laravel Cloud Dashboard**: https://cloud.laravel.com
2. **Pilih Project Anda**
3. **Klik "Logs"** di sidebar kiri
4. **Pilih "Build Logs"** atau "Deploy Logs"
5. **Scroll ke bawah** cari error message
6. **Copy** error tersebut dan cari solusinya di bawah

---

## STEP 2: Kemungkinan Error & Solusi

### 🔴 Error 1: APP_KEY Not Set

**Error Message:**
```
RuntimeException: No application encryption key has been specified
```

**Solusi:**
1. Di Laravel Cloud Dashboard > Environment Variables
2. Tambahkan:
   ```
   APP_KEY = base64:GENERATE_NEW_KEY
   ```
3. Atau jalankan di Console:
   ```bash
   php artisan key:generate
   ```

---

### 🔴 Error 2: Database Connection Failed

**Error Message:**
```
SQLSTATE[08006] connection refused
```

**Solusi:**
1. Pastikan environment variables sudah benar:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=ep-old-butterfly-a1sijrn0.aws-ap-southeast-1.pg.laravel.cloud
   DB_PORT=5432
   DB_DATABASE=laravel
   DB_USERNAME=laravel
   DB_PASSWORD=npg_TGOaFrI1BXu2
   DB_SSLMODE=prefer
   ```
2. Pastikan database PostgreSQL sudah aktif di Laravel Cloud
3. Test koneksi di Console:
   ```bash
   php artisan tinker
   >>> DB::connection()->getPdo();
   ```

---

### 🔴 Error 3: Migration Failed

**Error Message:**
```
SQLSTATE[42P01]: Undefined table: 7 ERROR
```

**Solusi:**
Jalankan migration manual di Console:
```bash
php artisan migrate --force
```

---

### 🔴 Error 4: Build Failed - Composer Install

**Error Message:**
```
composer install failed
```

**Solusi:**
1. Pastikan `composer.json` valid
2. Jalankan lokal dulu:
   ```bash
   composer install
   composer update
   ```
3. Commit dan push lagi:
   ```bash
   git add composer.lock
   git commit -m "Update composer.lock"
   git push
   ```

---

### 🔴 Error 5: PHP Version Mismatch

**Error Message:**
```
PHP version not supported
```

**Solusi:**
1. Cek `composer.json`:
   ```json
   "require": {
       "php": "^8.1"
   }
   ```
2. Pastikan tidak ada versi PHP yang terlalu spesifik

---

## STEP 3: Deploy Ulang dari Awal

### Option A: Redeploy via Dashboard

1. **Buka Laravel Cloud Dashboard**
2. **Pilih Project**
3. **Klik "Settings"**
4. **Scroll ke bawah** → Klik "Redeploy"
5. **Pilih branch** yang benar (main/master)
6. **Klik Deploy**

### Option B: Deploy via CLI

```bash
# Install Laravel Cloud CLI
composer global require laravel/cloud-cli

# Login
laravel cloud login

# Deploy
laravel cloud deploy

# Cek logs jika error
laravel cloud logs --tail
```

### Option C: Manual Deploy

1. **Hapus project lama** di Laravel Cloud (jika perlu)
2. **Create project baru**
3. **Connect repository**
4. **Configure environment variables** (lihat STEP 4)
5. **Deploy**

---

## STEP 4: Pastikan Environment Variables Lengkap

Di Laravel Cloud Dashboard → Settings → Environment Variables, tambahkan:

```bash
APP_NAME="Sistem Peminjaman Alat"
APP_ENV=production
APP_DEBUG=false
APP_KEY=<YOUR_APP_KEY>
APP_URL=<YOUR_APP_URL>

DB_CONNECTION=pgsql
DB_HOST=ep-old-butterfly-a1sijrn0.aws-ap-southeast-1.pg.laravel.cloud
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=npg_TGOaFrI1BXu2
DB_SSLMODE=prefer

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public
```

**PENTING**: Ganti `<YOUR_APP_KEY>` dengan key yang di-generate:
```bash
php artisan key:generate --show
```

---

## STEP 5: Test Lokally Dulu

Sebelum deploy lagi, pastikan aplikasinya jalan di lokal:

```bash
# Clear semua cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Test di lokal
php artisan serve
```

Buka: http://localhost:8000

Jika jalan di lokal, berarti masalahnya di konfigurasi cloud.

---

## STEP 6: Cek File Penting

Pastikan file-file ini ada di repository:

- ✅ `composer.json`
- ✅ `composer.lock`
- ✅ `.env.example`
- ✅ `artisan`
- ✅ `public/index.php`
- ✅ `bootstrap/app.php`
- ✅ `routes/web.php`

---

## STEP 7: Common Issues Laravel Cloud

### Issue 1: Storage Permission
```bash
php artisan storage:link
```

### Issue 2: Session Table Not Found
```bash
php artisan session:table
php artisan migrate
```

### Issue 3: Cache Driver Error
Pastikan: `CACHE_STORE=database`

### Issue 4: Queue Error
Pastikan: `QUEUE_CONNECTION=sync` (untuk awal)

---

## 📞 Kontak Support Laravel Cloud

Jika masih error setelah semua step di atas:

1. **Siapkan Informasi:**
   - Project name
   - Deployment ID
   - Error logs (copy semua)
   
2. **Kontak Support:**
   - Email: support@laravel.com
   - Forum: https://github.com/laravel/cloud/issues
   - Discord: Laravel Discord server

3. **Template Email Support:**
```
Subject: Deployment Failed - Unable to Determine Cause

Hi Laravel Cloud Support,

My deployment failed with error: "The deployment has failed unexpectedly, 
and we were unable to determine the cause."

Project: <YOUR_PROJECT_NAME>
Deployment ID: <FROM DASHBOARD>

Build Logs: <ATTACH LOGS>
Deploy Logs: <ATTACH LOGS>

I've tried:
- Checking environment variables
- Running migrations manually
- Clearing cache
- Redeploying multiple times

Please help. Thank you!
```

---

## ✅ Checklist Sebelum Deploy Lagi

- [ ] APP_KEY sudah di-set
- [ ] Database credentials sudah benar
- [ ] Environment variables sudah lengkap
- [ ] Aplikasi jalan di lokal
- [ ] Semua file penting sudah di-commit
- [ ] composer.lock sudah di-push
- [ ] Branch yang benar (main/master)
- [ ] PHP version compatible (^8.1)
- [ ] Storage link sudah dibuat
- [ ] Migration sudah dijalankan

---

**Last Updated**: 2026-04-07
