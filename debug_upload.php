<?php
/**
 * Debug script untuk test upload foto pengembalian
 * Akses: http://localhost/Peminjaman_alatNEW/debug_upload.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "<h2>🔍 Debug Upload Foto Pengembalian</h2>";
echo "<hr>";

// 1. Cek kolom database
echo "<h3>1. Cek Kolom Database</h3>";
$hasBuktiFoto = Schema::hasColumn('pengembalian', 'bukti_foto');
echo "<p>Kolom 'bukti_foto' ada: <strong>" . ($hasBuktiFoto ? '✅ YA' : '❌ TIDAK') . "</strong></p>";

if ($hasBuktiFoto) {
    $columns = DB::select("DESCRIBE pengembalian");
    echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        $highlight = ($col->Field == 'bukti_foto') ? 'background: #ffffcc; font-weight: bold;' : '';
        echo "<tr style='$highlight'>";
        echo "<td>" . htmlspecialchars($col->Field) . "</td>";
        echo "<td>" . htmlspecialchars($col->Type) . "</td>";
        echo "<td>" . htmlspecialchars($col->Null) . "</td>";
        echo "<td>" . htmlspecialchars($col->Default ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// 2. Cek folder upload
echo "<h3>2. Cek Folder Upload</h3>";
$uploadPath = public_path('uploads/bukti_pengembalian');
echo "<p>Path: <code>$uploadPath</code></p>";

if (file_exists($uploadPath)) {
    echo "<p>✅ Folder ADA</p>";
    echo "<p>Writeable: " . (is_writable($uploadPath) ? '✅ YA' : '❌ TIDAK') . "</p>";
} else {
    echo "<p>❌ Folder TIDAK ADA</p>";
    echo "<p>🔧 Mencoba membuat folder...</p>";
    if (@mkdir($uploadPath, 0755, true)) {
        echo "<p>✅ Folder berhasil dibuat</p>";
    } else {
        echo "<p>❌ Gagal membuat folder. Cek permission!</p>";
    }
}

// 3. Cek config file upload
echo "<h3>3. Cek Konfigurasi PHP</h3>";
$maxUpload = ini_get('upload_max_filesize');
$maxPost = ini_get('post_max_size');
echo "<p>upload_max_filesize: <strong>$maxUpload</strong></p>";
echo "<p>post_max_size: <strong>$maxPost</strong></p>";

// 4. Cek pengembalian terakhir
echo "<h3>4. Cek Data Pengembalian Terakhir</h3>";
$lastReturn = DB::table('pengembalian')->orderBy('id_pengembalian', 'desc')->first();
if ($lastReturn) {
    echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    echo "<tr><td>id_pengembalian</td><td>" . $lastReturn->id_pengembalian . "</td></tr>";
    echo "<tr><td>bukti_foto</td><td><code>" . ($lastReturn->bukti_foto ?? 'NULL') . "</code></td></tr>";
    echo "<tr><td>kondisi_alat</td><td>" . $lastReturn->kondisi_alat . "</td></tr>";
    echo "</table>";
} else {
    echo "<p>Belum ada data pengembalian</p>";
}

// 5. Test upload manual
echo "<h3>5. Test Upload Manual</h3>";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    echo "<p>📤 File upload detected!</p>";
    
    $file = $_FILES['test_file'];
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Property</th><th>Value</th></tr>";
    echo "<tr><td>Name</td><td>" . htmlspecialchars($file['name']) . "</td></tr>";
    echo "<tr><td>Type</td><td>" . htmlspecialchars($file['type']) . "</td></tr>";
    echo "<tr><td>Size</td><td>" . $file['size'] . " bytes</td></tr>";
    echo "<tr><td>Error Code</td><td>" . $file['error'] . "</td></tr>";
    echo "</table>";
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $destPath = public_path('uploads/bukti_pengembalian/test_' . time() . '.jpg');
        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            echo "<p style='color: green;'>✅ File berhasil diupload ke: <code>$destPath</code></p>";
        } else {
            echo "<p style='color: red;'>❌ Gagal memindahkan file. Cek permission folder!</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Upload error code: " . $file['error'] . "</p>";
    }
}

echo "<form method='POST' enctype='multipart/form-data'>";
echo "<label><strong>Test Upload Foto:</strong></label><br>";
echo "<input type='file' name='test_file' accept='image/*'>";
echo "<button type='submit'>Test Upload</button>";
echo "</form>";

echo "<hr>";
echo "<p><a href=''>🔄 Refresh</a> | <a href='/'>🏠 Home</a></p>";

?>
