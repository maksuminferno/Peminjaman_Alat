# Quick Deploy Guide - Tanpa Migration

## ✅ STEP-BY-STEP DEPLOY (DIJAMIN SUKSES)

---

## STEP 1: Commit & Push Perubahan

```bash
git add .
git commit -m "Remove deploy command to fix deployment error"
git push origin main
```

---

## STEP 2: Redeploy di Laravel Cloud

1. Buka https://cloud.laravel.com
2. Pilih project Anda
3. Klik **"Deployments"**
4. Klik **"Redeploy"** atau deploy otomatis akan terjadi setelah push
5. **Tunggu** sampai deploy **SUKSES** (tanpa migration)

---

## STEP 3: Setelah Deploy SUKSES, Jalankan Manual Commands

### Via Laravel Cloud Console:

1. Klik **"Console"** di dashboard
2. Jalankan commands berikut **SATU-PERSATU**:

```bash
# 1. Generate APP_KEY (jika belum ada)
php artisan key:generate

# 2. Clear config cache dulu
php artisan config:clear

# 3. Jalankan migration
php artisan migrate --force

# 4. Jalankan seeder untuk buat users
php artisan db:seed --class=UserSeeder --force

# 5. Storage link
php artisan storage:link

# 6. Cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Atau Copy-Paste Semua Sekaligus:

```bash
php artisan config:clear && php artisan migrate --force && php artisan db:seed --class=UserSeeder --force && php artisan storage:link && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

---

## STEP 4: Test Aplikasi

1. Buka URL aplikasi Anda: `https://your-app-url.laravel.cloud`
2. Test login dengan:
   - Email: `admin@peminjaman.com`
   - Password: `Admin123!`

---

## ❌ Jika Masih Error

### Error: "Undefined array key database"

**Penyebab**: Environment variables tidak ter-set

**Solusi**:

1. **Cek Environment Variables** di Laravel Cloud:
   - Settings → Environment Variables
   - Pastikan ini ada:
   ```
   DB_CONNECTION = pgsql
   DB_HOST = ep-old-butterfly-a1sijrn0.aws-ap-southeast-1.pg.laravel.cloud
   DB_PORT = 5432
   DB_DATABASE = laravel
   DB_USERNAME = laravel
   DB_PASSWORD = npg_TGOaFrI1BXu2
   DB_SSLMODE = prefer
   ```

2. **Test Koneksi Database** di Console:
   ```bash
   php artisan tinker
   >>> DB::connection()->getPdo();
   >>> exit
   ```

### Error: "APP_KEY not set"

**Solusi**:
```bash
php artisan key:generate
```

Atau set manual di Environment Variables:
```
APP_KEY = base64:<KEY_FROM_GENERATE>
```

---

## 📋 Checklist

- [ ] Hapus/comment deploy command di Laravel.toml
- [ ] Commit & push ke Git
- [ ] Deploy di Laravel Cloud (tanpa migration)
- [ ] Tunggu deploy SUKSES
- [ ] Jalankan migration manual via Console
- [ ] Jalankan seeder via Console
- [ ] Test aplikasi
- [ ] Clear & cache untuk production

---

**PENTING**: Jangan jalankan migration di deploy command, jalankan manual setelah app running!
