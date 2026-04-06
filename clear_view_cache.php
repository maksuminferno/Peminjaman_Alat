<?php

/**
 * Clear Laravel view cache
 * Run this file via browser: http://localhost/Peminjaman_alatNEW/clear_view_cache.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Artisan;

echo "<h2>Clear View Cache</h2>";

try {
    Artisan::call('view:clear');
    echo "<p style='color: green;'>✓ View cache cleared successfully!</p>";
    echo "<p><a href='/'>Go to Home</a> | <a href=''>Refresh</a></p>";
} catch (\Exception $e) {
    echo "<p style='color: red;'><strong>✗ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}

?>
