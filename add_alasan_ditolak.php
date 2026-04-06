<?php

/**
 * Add alasan_ditolak column to peminjaman table
 * Run this file via browser: http://localhost/Peminjaman_alatNEW/add_alasan_ditolak.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "<h2>Add Alasan Ditolak Column</h2>";

try {
    // Check if alasan_ditolak column exists
    if (!Schema::hasColumn('peminjaman', 'alasan_ditolak')) {
        // Add alasan_ditolak column
        DB::statement("
            ALTER TABLE peminjaman 
            ADD COLUMN alasan_ditolak TEXT NULL AFTER status
        ");
        echo "<p style='color: green;'>✓ alasan_ditolak column added successfully!</p>";
    } else {
        echo "<p style='color: blue;'>ℹ alasan_ditolak column already exists</p>";
    }

    echo "<hr>";
    echo "<p style='color: green; font-weight: bold;'>✓ Setup completed successfully!</p>";
    echo "<p><strong>Next step:</strong> The rejection reason feature is now available.</p>";
    echo "<p><a href='/petugas/peminjaman'>Go to Peminjaman Page</a> | <a href='/'>Go to Home</a> | <a href=''>Refresh</a></p>";

} catch (\Exception $e) {
    echo "<p style='color: red;'><strong>✗ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

?>
