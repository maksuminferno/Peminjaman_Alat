# Quick Fix - Deployment Failed

## 🚨 Error: "Deployment failed - unable to determine the cause"

### ✅ SOLUSI CEPAT (PILIH SALAH SATU)

---

## OPTION 1: Redeploy dengan Environment Variables yang Benar

### Step 1: Buka Laravel Cloud Dashboard
1. Login ke https://cloud.laravel.com
2. Pilih project Anda
3. Klik **"Settings"**
4. Klik **"Environment Variables"**

### Step 2: Hapus Semua Variable Lama
Hapus semua environment variables yang ada

### Step 3: Tambahkan Variable Baru Satu-per-Satu

```
APP_NAME = Sistem Peminjaman Alat
APP_ENV = production
APP_DEBUG = false
```

**PENTING**: Untuk APP_KEY, generate dulu:
```bash
# Jalankan di komputer lokal
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe artisan key:generate --show
```

Copy hasilnya, contoh: `base64:xxxxxxxxxxxxx`

```
APP_KEY = base64:xxxxxxxxxxxxx  <-- GANTI DENGAN YANG ANDA DAPAT
APP_URL = https://your-app-url.laravel.cloud  <-- SESUAIKAN
```

Database variables:
```
DB_CONNECTION = pgsql
DB_HOST = ep-old-butterfly-a1sijrn0.aws-ap-southeast-1.pg.laravel.cloud
DB_PORT = 5432
DB_DATABASE = laravel
DB_USERNAME = laravel
DB_PASSWORD = npg_TGOaFrI1BXu2
DB_SSLMODE = prefer
```

Cache & Session:
```
SESSION_DRIVER = database
CACHE_STORE = database
QUEUE_CONNECTION = sync
FILESYSTEM_DISK = public
```

### Step 4: Redeploy
1. Klik **"Deployments"** di sidebar
2. Klik **"Redeploy"** atau **"Deploy Again"**
3. Tunggu proses selesai

### Step 5: Jalankan Migration (Jika Deploy Sukses)
1. Buka **Console** di Laravel Cloud
2. Jalankan:
```bash
php artisan migrate --force
php artisan db:seed --class=UserSeeder --force
php artisan storage:link
```

---

## OPTION 2: Deploy dari Awal (Clean Deploy)

### Step 1: Hapus Project Lama
1. Laravel Cloud Dashboard
2. Settings → Danger Zone
3. **Delete Project**

### Step 2: Bersihkan Git (Optional)
```bash
# Di komputer lokal
git status
git add .
git commit -m "Clean deploy preparation"
git push origin main
```

### Step 3: Create Project Baru
1. Laravel Cloud Dashboard
2. **Create Project**
3. **Connect Repository** (pilih Git repo Anda)
4. **Configure Build Settings**:
   - PHP Version: **8.3**
   - Region: **Asia Pacific (Singapore)**
   - Branch: **main** atau **master**

### Step 4: Add Environment Variables
Copy-paste SEMUA variable dari OPTION 1 Step 3

### Step 5: Deploy
Klik **"Deploy Now"**

---

## OPTION 3: Deploy Manual Via CLI (Advanced)

### Install Laravel Cloud CLI
```bash
composer global require laravel/cloud-cli
```

### Login
```bash
laravel cloud login
```

### Deploy
```bash
cd C:\laragon\www\Peminjaman_alatNEW
laravel cloud deploy
```

### Monitor Logs
```bash
laravel cloud logs --tail
```

---

## 🔍 Cek Error Detail

Jika masih gagal, cek error detailnya:

### Via Dashboard:
1. Laravel Cloud Dashboard
2. Klik project Anda
3. Klik **"Logs"**
4. Pilih **"Build Logs"** - cari error saat build
5. Pilih **"Deploy Logs"** - cari error saat deploy

### Via CLI:
```bash
laravel cloud logs
```

### Error yang Sering Muncul:

| Error | Solusi |
|-------|--------|
| `No application encryption key` | Set APP_KEY |
| `connection refused` | Cek DB credentials |
| `table doesn't exist` | Run migration |
| `composer install failed` | Update composer.lock |
| `PHP version not supported` | Ubah ke PHP 8.3 |

---

## ✅ Checklist Deploy Sukses

Sebelum deploy lagi, pastikan:

- [ ] APP_KEY sudah di-generate dan diset
- [ ] Database credentials sudah benar
- [ ] Semua environment variables sudah lengkap
- [ ] Aplikasi jalan di lokal (test dulu)
- [ ] File composer.lock sudah di-push ke Git
- [ ] Branch yang benar (main/master)
- [ ] PHP version 8.3 di settings
- [ ] Region Asia Pacific (Singapore)

---

## 🆘 Masih Gagal?

### Template Kontak Support:

```
Subject: Deployment Failed - Need Urgent Help

Hi Laravel Cloud Team,

I'm experiencing deployment failure with error:
"The deployment has failed unexpectedly, and we were unable to determine the cause."

Project Details:
- Project Name: [YOUR_PROJECT_NAME]
- Deployment ID: [FROM DASHBOARD]
- Repository: [YOUR_GIT_REPO]

Environment:
- PHP: 8.3
- Database: PostgreSQL (Laravel Cloud)
- Region: Asia Pacific Singapore

Build Logs: [ATTACH SCREENSHOT/LOGS]
Deploy Logs: [ATTACH SCREENSHOT/LOGS]

Steps I've tried:
1. Checked all environment variables
2. Verified database credentials
3. Generated APP_KEY
4. Cleared all cache
5. Multiple redeploy attempts

Please assist. Thank you!
```

**Contact:**
- Email: support@laravel.com
- GitHub Issues: https://github.com/laravel/cloud/issues
- Discord: Laravel Discord Server

---

## 💡 Tips Penting

1. **SELALU test di lokal dulu sebelum deploy**
   ```bash
   php artisan serve
   # Test: http://localhost:8000
   ```

2. **Jangan commit .env ke Git**
   ```bash
   # Pastikan .env ada di .gitignore
   echo ".env" >> .gitignore
   ```

3. **Gunakan .env.example sebagai template**
   ```bash
   # Copy .env.example ke server, lalu isi manual
   ```

4. **Simpan APP_KEY di tempat aman**
   - Jangan sampai hilang
   - Jika hilang, semua encrypted data tidak bisa dibaca

---

**RECOMMENDED**: Mulai dari OPTION 1 (Redeploy dengan Env Vars yang benar)

Good luck! 🚀
