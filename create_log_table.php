<?php

/**
 * Create log_aktivitas table
 * Run this file via browser: http://localhost/Peminjaman_alatNEW/create_log_table.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "<h2>Create Log Aktivitas Table</h2>";

try {
    // Check if log_aktivitas table exists
    if (!Schema::hasTable('log_aktivitas')) {
        // Create log_aktivitas table
        DB::statement("
            CREATE TABLE log_aktivitas (
                id_log INT AUTO_INCREMENT PRIMARY KEY,
                id_user BIGINT UNSIGNED NULL,
                aktivitas TEXT NOT NULL,
                waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE SET NULL
            )
        ");
        echo "<p style='color: green;'>✓ Log aktivitas table created successfully!</p>";
    } else {
        echo "<p style='color: blue;'>ℹ Log aktivitas table already exists</p>";
    }

    echo "<hr>";
    echo "<p style='color: green; font-weight: bold;'>✓ Setup completed successfully!</p>";
    echo "<p><strong>Log Aktivitas page is now active!</strong></p>";
    echo "<p><a href='/admin/log-aktivitas'>Go to Log Aktivitas Page</a> | <a href='/'>Go to Home</a> | <a href=''>Refresh</a></p>";

} catch (\Exception $e) {
    echo "<p style='color: red;'><strong>✗ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

?>
