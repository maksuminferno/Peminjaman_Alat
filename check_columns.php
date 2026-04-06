<?php

$baseDir = __DIR__ . '/resources/views/';
$filesToCheck = [
    'admin/users.blade.php',
    'admin/alat.blade.php',
    'admin/kategori.blade.php',
    'admin/peminjaman.blade.php',
    'admin/pengembalian.blade.php',
    'admin/log_aktivitas.blade.php',
    'petugas/pengembalian.blade.php',
    'petugas/laporan.blade.php',
    'peminjam/return.blade.php',
    'peminjam/history.blade.php',
];

echo "=== MEMERIKSA KOLOM TABEL ===\n\n";

foreach ($filesToCheck as $file) {
    $filePath = $baseDir . $file;
    if (!file_exists($filePath)) continue;
    
    $content = file_get_contents($filePath);
    
    // Check for datatable class
    if (strpos($content, 'class="table table-hover datatable"') === false) {
        continue;
    }
    
    // Count th tags in thead
    preg_match_all('/<thead>.*?<tr>(.*?)<\/tr>.*?<\/thead>/s', $content, $theadMatches);
    $thCount = 0;
    foreach ($theadMatches[1] as $match) {
        preg_match_all('/<th[^>]*>/i', $match, $thMatches);
        $thCount = count($thMatches[0]);
        break; // Assume only one thead row
    }
    
    // Count td tags in first tbody tr
    preg_match_all('/<tbody>.*?<tr>(.*?)<\/tr>.*?<\/tbody>/s', $content, $tbodyMatches);
    $tdCount = 0;
    if (isset($tbodyMatches[1][0])) {
        preg_match_all('/<td[^>]*>/i', $tbodyMatches[1][0], $tdMatches);
        $tdCount = count($tdMatches[0]);
    }
    
    // Get colspan
    preg_match('/colspan="(\d+)"/', $content, $colspanMatch);
    $colspan = isset($colspanMatch[1]) ? $colspanMatch[1] : 'N/A';
    
    $status = ($thCount === $tdCount && $thCount === (int)$colspan) ? '✅ OK' : '❌ MISMATCH';
    
    echo "File: {$file}\n";
    echo "  <th>: {$thCount} | <td>: {$tdCount} | colspan: {$colspan} => {$status}\n\n";
}

echo "=== SELESAI ===\n";
