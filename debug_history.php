<?php
// Script debugging untuk mengecek data peminjaman user ID 2

require_once __DIR__.'/vendor/autoload.php';

// Set up Laravel environment
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

// Simulasikan autentikasi untuk user ID 2
Auth::loginUsingId(2);

// Ambil data peminjaman untuk user ID 2
$peminjaman = Peminjaman::where('id_user', 2)
    ->with(['detailPeminjaman.alat', 'pengembalian'])
    ->orderBy('created_at', 'desc')
    ->get();

echo "Jumlah peminjaman ditemukan untuk user ID 2: " . $peminjaman->count() . "\n\n";

if ($peminjaman->count() > 0) {
    foreach ($peminjaman as $p) {
        echo "ID Peminjaman: " . $p->id_peminjaman . "\n";
        echo "Tanggal Pinjam: " . $p->tanggal_pinjam . "\n";
        echo "Tanggal Kembali Rencana: " . $p->tanggal_kembali_rencana . "\n";
        echo "Status: " . $p->status . "\n";
        echo "Created At: " . $p->created_at . "\n";
        
        foreach ($p->detailPeminjaman as $detail) {
            echo "  - Alat: " . $detail->alat->nama_alat . " (Jumlah: " . $detail->jumlah . ")\n";
        }
        
        echo "---\n";
    }
} else {
    echo "Tidak ditemukan data peminjaman untuk user ID 2\n";
}

// Coba juga ambil 5 terbaru seperti di view
$recentPeminjaman = Peminjaman::where('id_user', 2)
    ->with(['detailPeminjaman.alat', 'pengembalian'])
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

echo "\nJumlah peminjaman 5 terbaru: " . $recentPeminjaman->count() . "\n";