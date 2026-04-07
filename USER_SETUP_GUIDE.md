# Panduan Menambahkan User Account

## ✅ Default Users (Sudah Dibuat)

User berikut sudah dibuat otomatis via UserSeeder:

| Role | Email | Username | Password |
|------|-------|----------|----------|
| **Admin** | admin@peminjaman.com | admin | Admin123! |
| **Petugas 1** | petugas1@peminjaman.com | petugas1 | Petugas123! |
| **Petugas 2** | petugas2@peminjaman.com | petugas2 | Petugas123! |
| **Peminjam 1** | john@example.com | johndoe | User123! |
| **Peminjam 2** | jane@example.com | janesmith | User123! |
| **Peminjam 3** | ahmad@example.com | ahmadrizki | User123! |

**Note:** Login bisa menggunakan **Email** atau **Username**

---

## Cara 1: Via Laravel Cloud Console (Tinker)

### Langkah-langkah:

1. **Buka Laravel Cloud Dashboard**
   - Login ke https://cloud.laravel.com
   - Pilih project Anda
   - Klik "Console" atau "Run Command"

2. **Jalankan Tinker Command**
   ```bash
   php artisan tinker
   ```

3. **Buat User Baru**
   ```php
   // Untuk Admin
   $user = new \App\Models\User;
   $user->nama = 'Admin Utama';
   $user->email = 'admin@peminjaman.com';
   $user->password = bcrypt('password123');
   $user->role = 'admin';
   $user->save();
   
   // Untuk Petugas
   $petugas = new \App\Models\User;
   $petugas->nama = 'Petugas Perpustakaan';
   $petugas->email = 'petugas@peminjaman.com';
   $petugas->password = bcrypt('password123');
   $petugas->role = 'petugas';
   $petugas->save();
   
   // Untuk Peminjam
   $peminjam = new \App\Models\User;
   $peminjam->nama = 'John Doe';
   $peminjam->email = 'john@example.com';
   $peminjam->password = bcrypt('password123');
   $peminjam->role = 'peminjam';
   $peminjam->save();
   ```

4. **Verify User**
   ```php
   // Cek semua user
   \App\Models\User::all();
   
   // Cek user tertentu
   \App\Models\User::where('email', 'admin@peminjaman.com')->first();
   ```

---

## Cara 2: Menggunakan Seeder (Recommended untuk Multiple Users)

### Step 1: Buat Seeder File

Buat file: `database/seeders/UserSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'nama' => 'Administrator',
            'email' => 'admin@peminjaman.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Petugas User 1
        User::create([
            'nama' => 'Petugas 1 - Peminjaman',
            'email' => 'petugas1@peminjaman.com',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
        ]);

        // Petugas User 2
        User::create([
            'nama' => 'Petugas 2 - Pengembalian',
            'email' => 'petugas2@peminjaman.com',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
        ]);

        // Peminjam User 1
        User::create([
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => 'peminjam',
        ]);

        // Peminjam User 2
        User::create([
            'nama' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'role' => 'peminjam',
        ]);

        // Peminjam User 3
        User::create([
            'nama' => 'Ahmad Rizki',
            'email' => 'ahmad@example.com',
            'password' => Hash::make('password123'),
            'role' => 'peminjam',
        ]);
    }
}
```

### Step 2: Daftarkan di DatabaseSeeder

Edit file: `database/seeders/DatabaseSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            // Tambahkan seeder lainnya jika ada
            // KategoriSeeder::class,
            // AlatSeeder::class,
        ]);
    }
}
```

### Step 3: Jalankan Seeder di Laravel Cloud

Via Console di Laravel Cloud Dashboard:
```bash
php artisan db:seed --class=UserSeeder --force
```

Atau untuk menjalankan semua seeder:
```bash
php artisan db:seed --force
```

---

## Cara 3: Via SQL Query (Direct Database)

Jika Anda punya akses langsung ke database PostgreSQL:

### Via Laravel Cloud Console:
```bash
php artisan tinker
```

Lalu jalankan:
```php
DB::table('users')->insert([
    'nama' => 'Nama User',
    'email' => 'email@example.com',
    'password' => bcrypt('password123'),
    'role' => 'peminjam', // admin, petugas, atau peminjam
    'created_at' => now(),
    'updated_at' => now(),
]);
```

### Via pgAdmin atau Database Client:
```sql
INSERT INTO users (nama, email, password, role, created_at, updated_at) 
VALUES (
    'Nama User',
    'email@example.com',
    '$2y$12$...', -- Hash password Anda
    'peminjam',
    NOW(),
    NOW()
);
```

---

## Cara 4: Buat Registration Page (Self-Service)

### Step 1: Buat Controller Method

Edit: `app/Http/Controllers/AuthController.php`

```php
public function showRegisterForm()
{
    return view('auth.register');
}

public function register(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    User::create([
        'nama' => $request->nama,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'peminjam', // Default role
    ]);

    return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
}
```

### Step 2: Tambahkan Route

Edit: `routes/web.php`

```php
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
```

### Step 3: Buat View Register

Buat: `resources/views/auth/register.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Register</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## Default Users untuk Testing

Berikut adalah user yang bisa dibuat untuk testing:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@peminjaman.com | password123 |
| Petugas | petugas@peminjaman.com | password123 |
| Peminjam | user@peminjaman.com | password123 |

---

## Tips Keamanan Production

⚠️ **PENTING**: Setelah deploy ke production:

1. **Ganti password default**
   ```php
   // Via tinker
   $user = User::where('email', 'admin@peminjaman.com')->first();
   $user->password = Hash::make('your-strong-password-here');
   $user->save();
   ```

2. **Hapus user testing** (jika ada)
   ```php
   User::where('email', 'LIKE', '%test%')->delete();
   ```

3. **Disable registration** (jika tidak diperlukan)
   - Hapus/comment route register
   - Atau tambahkan approval process

---

## Quick Start - Copy Paste Ready

Jalankan ini di Laravel Cloud Console untuk membuat 3 user sekaligus:

```php
// Copy paste semua code ini ke tinker
$users = [
    [
        'nama' => 'Admin Utama',
        'email' => 'admin@peminjaman.com',
        'password' => bcrypt('Admin123!'),
        'role' => 'admin',
    ],
    [
        'nama' => 'Petugas Library',
        'email' => 'petugas@peminjaman.com',
        'password' => bcrypt('Petugas123!'),
        'role' => 'petugas',
    ],
    [
        'nama' => 'User Peminjam',
        'email' => 'user@peminjaman.com',
        'password' => bcrypt('User123!'),
        'role' => 'peminjam',
    ],
];

foreach ($users as $userData) {
    $user = \App\Models\User::create($userData);
    echo "Created: {$user->email} ({$user->role})\n";
}

echo "\nDone! " . count($users) . " users created.\n";
```

---

**Last Updated**: 2026-04-07
