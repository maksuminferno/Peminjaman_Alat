<?php

/**
 * Manual migration script to add kondisi_alat and deskripsi_kerusakan columns
 * Run this file via browser: http://localhost/Peminjaman_alatNEW/run_fix_migration.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "<h2>Migration Fix Script</h2>";
echo "<p>Adding kondisi_alat and deskripsi_kerusakan columns to pengembalian table...</p>";

try {
    // Check if columns exist and add them if they don't
    $hasKondisiAlat = Schema::hasColumn('pengembalian', 'kondisi_alat');
    $hasDeskripsiKerusakan = Schema::hasColumn('pengembalian', 'deskripsi_kerusakan');
    
    echo "<p><strong>Current state:</strong></p>";
    echo "<ul>";
    echo "<li>kondisi_alat column exists: " . ($hasKondisiAlat ? 'Yes' : 'No') . "</li>";
    echo "<li>deskripsi_kerusakan column exists: " . ($hasDeskripsiKerusakan ? 'Yes' : 'No') . "</li>";
    echo "</ul>";
    
    if (!$hasKondisiAlat) {
        DB::statement("ALTER TABLE pengembalian ADD COLUMN kondisi_alat VARCHAR(20) DEFAULT 'baik' AFTER denda");
        echo "<p style='color: green;'>✓ Added kondisi_alat column successfully</p>";
    } else {
        echo "<p style='color: blue;'>ℹ kondisi_alat column already exists</p>";
    }
    
    if (!$hasDeskripsiKerusakan) {
        DB::statement("ALTER TABLE pengembalian ADD COLUMN deskripsi_kerusakan TEXT NULL AFTER kondisi_alat");
        echo "<p style='color: green;'>✓ Added deskripsi_kerusakan column successfully</p>";
    } else {
        echo "<p style='color: blue;'>ℹ deskripsi_kerusakan column already exists</p>";
    }
    
    // Update migration record
    $migrationName = '2026_02_26_000001_add_deskripsi_kerusakan_to_pengembalian_table';
    $exists = DB::table('migrations')->where('migration', $migrationName)->exists();
    
    if (!$exists) {
        DB::table('migrations')->insert([
            'migration' => $migrationName,
            'batch' => DB::table('migrations')->max('batch') + 1
        ]);
        echo "<p style='color: green;'>✓ Migration record added successfully</p>";
    }
    
    echo "<hr>";
    echo "<p style='color: green; font-weight: bold;'>✓ Migration completed successfully!</p>";
    echo "<p><a href=''>Refresh this page</a> | <a href='/'>Go to Home</a></p>";
    
} catch (\Exception $e) {
    echo "<p style='color: red;'><strong>✗ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

?>
