<?php

/**
 * Add disetujui_oleh column to peminjaman table
 * Run this file via browser: http://localhost/Peminjaman_alatNEW/add_disetujui_oleh.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "<h2>Add Disetujui Oleh Column</h2>";

try {
    // Check if disetujui_oleh column exists
    if (!Schema::hasColumn('peminjaman', 'disetujui_oleh')) {
        // Add disetujui_oleh column
        DB::statement("
            ALTER TABLE peminjaman 
            ADD COLUMN disetujui_oleh BIGINT UNSIGNED NULL AFTER id_user,
            ADD FOREIGN KEY (disetujui_oleh) REFERENCES users(id_user) ON DELETE SET NULL
        ");
        echo "<p style='color: green;'>✓ disetujui_oleh column added successfully!</p>";
    } else {
        echo "<p style='color: blue;'>ℹ disetujui_oleh column already exists</p>";
    }

    echo "<hr>";
    echo "<p style='color: green; font-weight: bold;'>✓ Setup completed successfully!</p>";
    echo "<p><strong>Next step:</strong> The 'Disetujui Oleh' column is now available in the peminjaman table.</p>";
    echo "<p><a href='/admin/peminjaman'>Go to Peminjaman Page</a> | <a href='/'>Go to Home</a> | <a href=''>Refresh</a></p>";

} catch (\Exception $e) {
    echo "<p style='color: red;'><strong>✗ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

?>
