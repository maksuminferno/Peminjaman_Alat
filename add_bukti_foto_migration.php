<?php

/**
 * Script to add bukti_foto column to pengembalian table
 * Run this file via browser: http://localhost/Peminjaman_alatNEW/add_bukti_foto_migration.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "<h2>Adding bukti_foto Column to pengembalian Table</h2>";

try {
    // Check if column exists
    $hasBuktiFoto = Schema::hasColumn('pengembalian', 'bukti_foto');

    echo "<p><strong>Current state:</strong></p>";
    echo "<ul>";
    echo "<li>bukti_foto column exists: " . ($hasBuktiFoto ? 'Yes' : 'No') . "</li>";
    echo "</ul>";

    if (!$hasBuktiFoto) {
        // Add bukti_foto column
        DB::statement("ALTER TABLE pengembalian ADD COLUMN bukti_foto VARCHAR(255) NULL AFTER deskripsi_kerusakan");
        echo "<p style='color: green; font-weight: bold;'>✓ bukti_foto column added successfully!</p>";
        
        // Show table structure
        echo "<hr>";
        echo "<p><strong>Current pengembalian table structure:</strong></p>";
        $columns = DB::select("DESCRIBE pengembalian");
        echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($col->Field) . "</td>";
            echo "<td>" . htmlspecialchars($col->Type) . "</td>";
            echo "<td>" . htmlspecialchars($col->Null) . "</td>";
            echo "<td>" . htmlspecialchars($col->Default ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: blue;'>ℹ bukti_foto column already exists. No changes needed.</p>";
    }

    echo "<hr>";
    echo "<p style='color: green; font-weight: bold;'>✓ Migration completed successfully!</p>";
    echo "<p><a href=''>Refresh this page</a> | <a href='/'>Go to Home</a></p>";

} catch (\Exception $e) {
    echo "<p style='color: red;'><strong>✗ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

?>
