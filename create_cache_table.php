<?php

/**
 * Create cache table migration
 * Run this file via browser: http://localhost/Peminjaman_alatNEW/create_cache_table.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "<h2>Create Cache Table</h2>";

try {
    // Check if cache table exists
    if (!Schema::hasTable('cache')) {
        // Create cache table
        Schema::create('cache', function ($table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });
        echo "<p style='color: green;'>✓ Cache table created successfully!</p>";
    } else {
        echo "<p style='color: blue;'>ℹ Cache table already exists</p>";
    }

    // Check if cache_locks table exists
    if (!Schema::hasTable('cache_locks')) {
        // Create cache_locks table
        Schema::create('cache_locks', function ($table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
        echo "<p style='color: green;'>✓ Cache locks table created successfully!</p>";
    } else {
        echo "<p style='color: blue;'>ℹ Cache locks table already exists</p>";
    }

    echo "<hr>";
    echo "<p style='color: green; font-weight: bold;'>✓ Setup completed successfully!</p>";
    echo "<p><a href='/'>Go to Home</a> | <a href=''>Refresh</a></p>";

} catch (\Exception $e) {
    echo "<p style='color: red;'><strong>✗ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

?>
