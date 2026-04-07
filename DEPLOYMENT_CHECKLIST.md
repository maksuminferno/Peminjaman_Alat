# Deployment Checklist - Sistem Peminjaman Alat

## ✅ Pre-Deployment Checklist

### Environment Setup
- [x] File `.env.production` sudah dibuat
- [x] Database credentials sudah dikonfigurasi
- [x] APP_KEY akan di-generate saat deploy
- [x] APP_DEBUG=false untuk production
- [x] APP_URL akan disesuaikan dengan URL Laravel Cloud

### Database
- [x] Migration files sudah siap
- [x] Seeder files sudah siap (jika ada)
- [x] Database PostgreSQL sudah tersedia

### Code Quality
- [x] Middleware role-based sudah aktif
- [x] Routes sudah dikonfigurasi dengan benar
- [x] Controllers sudah lengkap
- [x] Views sudah lengkap

### Security
- [x] APP_DEBUG=false
- [x] Rate limiting (jika diperlukan)
- [x] CSRF protection aktif
- [x] XSS protection aktif

### Performance
- [x] View caching akan diaktifkan
- [x] Route caching akan diaktifkan
- [x] Config caching akan diaktifkan

## 📦 Deployment Steps

### Step 1: Generate APP_KEY
```bash
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe artisan key:generate --show
```
Copy output dan simpan untuk dimasukkan ke environment variables.

### Step 2: Push ke Git
```bash
git add .
git commit -m "Prepare for Laravel Cloud deployment"
git push origin main
```

### Step 3: Deploy di Laravel Cloud
1. Login ke https://cloud.laravel.com
2. Connect repository
3. Configure build settings
4. Add environment variables
5. Deploy!

### Step 4: Post-Deployment
```bash
# Run migrations
php artisan migrate --force

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link
php artisan storage:link
```

### Step 5: Test
- [ ] Login page works
- [ ] Admin can access /admin
- [ ] Peminjam can access /peminjam
- [ ] Petugas can access /petugas
- [ ] Database connection works
- [ ] File upload works (if any)
- [ ] All CRUD operations work

## 🔧 Environment Variables Required

```bash
APP_NAME="Sistem Peminjaman Alat"
APP_ENV=production
APP_KEY=<GENERATED_KEY>
APP_DEBUG=false
APP_URL=<YOUR_LARAVEL_CLOUD_URL>

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

## 📊 Database Tables Required

Pastikan semua tabel berikut sudah ter-create via migration:

- [ ] users
- [ ] kategori
- [ ] alat
- [ ] peminjaman
- [ ] detail_peminjaman
- [ ] pengembalian
- [ ] log_aktivitas
- [ ] cache
- [ ] sessions
- [ ] jobs (jika menggunakan queue)

## 🔍 Post-Deployment Verification

### 1. Health Check
```bash
curl https://your-app-url.laravel.cloud/up
# Should return: OK
```

### 2. Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo();
# Should return PDO instance
```

### 3. Check Routes
```bash
php artisan route:list
# Verify all routes are registered
```

### 4. Check Middleware
```bash
# Test role-based access
# Login as different roles and verify access
```

## 🚨 Common Issues & Solutions

### Issue: Database SSL Error
**Solution**: Set `DB_SSLMODE=prefer` atau `DB_SSLMODE=require`

### Issue: 403 Forbidden on Role Routes
**Solution**: 
1. Check user role in database
2. Make sure role matches middleware expectations
3. Check logs: `storage/logs/laravel.log`

### Issue: View Not Found
**Solution**: 
```bash
php artisan view:clear
php artisan config:clear
php artisan view:cache
```

### Issue: Session Not Working
**Solution**: 
1. Make sure `SESSION_DRIVER=database`
2. Run `php artisan session:table` and migrate
3. Check sessions table exists

## 📞 Support

Jika ada masalah saat deployment:
1. Cek log: `storage/logs/laravel.log`
2. Laravel Cloud Logs di dashboard
3. Dokumentasi: DEPLOYMENT.md

---

**Status**: ✅ READY FOR DEPLOYMENT
**Date**: 2026-04-07
