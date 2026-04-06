<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solusi Masalah Login - Sistem Peminjaman Alat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --orange-500: #f97316;
            --orange-600: #ea580c;
            --orange-700: #c2410c;
        }
        
        body {
            background-color: #f4f6f9;
            background: linear-gradient(to bottom, #fffaf5, #fff3e8);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            overflow: hidden;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .card-header {
            background: linear-gradient(135deg, #ffffff, var(--orange-50));
            border-bottom: 1px solid #fde68a;
            color: #2c3e50;
            text-align: center;
            position: relative;
        }
        
        .card-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 5px;
            height: 100%;
            background: var(--orange-500);
        }
        
        .solution-steps {
            counter-reset: step-counter;
        }
        
        .solution-step {
            counter-increment: step-counter;
            position: relative;
            padding-left: 40px;
            margin-bottom: 25px;
        }
        
        .solution-step::before {
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 0;
            width: 30px;
            height: 30px;
            background: var(--orange-500);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h4><i class="fas fa-question-circle me-2"></i>Solusi Masalah Login</h4>
            </div>
            <div class="card-body">
                <h5>Masalah: "Username atau password salah"</h5>
                <p>Ikuti langkah-langkah berikut untuk menyelesaikan masalah login:</p>
                
                <div class="solution-steps">
                    <div class="solution-step">
                        <h6>Cek Konfigurasi Database</h6>
                        <p>Periksa apakah database sudah terkoneksi dengan benar. Pastikan file <code>.env</code> sudah dikonfigurasi sesuai dengan database Anda.</p>
                    </div>
                    
                    <div class="solution-step">
                        <h6>Periksa Tabel Users</h6>
                        <p>Verifikasi bahwa tabel <code>users</code> sudah dibuat dan berisi data pengguna. Anda dapat membuka file <code>database/database.sqlite</code> menggunakan database browser seperti DB Browser for SQLite.</p>
                    </div>
                    
                    <div class="solution-step">
                        <h6>Import Data Pengguna</h6>
                        <p>Jika tabel users kosong, Anda perlu menambahkan data pengguna secara manual. Gunakan file SQL yang sudah disediakan:</p>
                        <ul>
                            <li>Buka file <code>insert_test_data.sql</code> yang ada di root direktori</li>
                            <li>Eksekusi semua perintah SQL tersebut ke database Anda</li>
                        </ul>
                    </div>
                    
                    <div class="solution-step">
                        <h6>Verifikasi Data Pengguna</h6>
                        <p>Pastikan data pengguna berikut sudah ada di tabel <code>users</code>:</p>
                        <ul>
                            <li><strong>Username:</strong> peminjam1, <strong>Password:</strong> password</li>
                            <li><strong>Username:</strong> peminjam2, <strong>Password:</strong> password</li>
                            <li><strong>Username:</strong> admin, <strong>Password:</strong> password</li>
                        </ul>
                    </div>
                    
                    <div class="solution-step">
                        <h6>Periksa Hash Password</h6>
                        <p>Pastikan password sudah di-hash dengan benar. Untuk password "password", hash yang benar adalah: <code>$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi</code></p>
                    </div>
                    
                    <div class="solution-step">
                        <h6>Clear Cache dan Sesuaikan</h6>
                        <p>Jika Anda menggunakan Laravel dengan caching, coba bersihkan cache:</p>
                        <pre class="bg-light p-2 rounded">php artisan config:clear
php artisan cache:clear</pre>
                    </div>
                </div>
                
                <h6 class="mt-4">Catatan Penting:</h6>
                <ul>
                    <li>Username yang valid: <code>peminjam1</code>, <code>peminjam2</code>, <code>admin</code></li>
                    <li>Password untuk semua akun: <code>password</code></li>
                    <li>Gunakan huruf kecil untuk username</li>
                    <li>Perhatikan besar kecil huruf saat mengetikkan username dan password</li>
                </ul>
                
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle me-2"></i>
                    Jika Anda menggunakan Laragon atau lingkungan pengembangan lokal lainnya, pastikan PHP dan Composer sudah terinstalasi dengan benar dan ditambahkan ke sistem PATH.
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="btn btn-primary me-2">Coba Login Lagi</a>
                    <a href="{{ route('setup') }}" class="btn btn-outline-secondary">Lihat Panduan Setup</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>