<?php

/**
 * Clear all Laravel caches
 * Run this file via browser: http://localhost/Peminjaman_alatNEW/clear_all_cache.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Artisan;

echo "<h2>Clear All Laravel Caches</h2>";
echo "<p>Clearing application caches...</p>";

try {
    echo "<h3>Step 1: Clearing application cache...</h3>";
    Artisan::call('cache:clear');
    echo "<p style='color: green;'>✓ Application cache cleared</p>";

    echo "<h3>Step 2: Clearing view cache...</h3>";
    Artisan::call('view:clear');
    echo "<p style='color: green;'>✓ View cache cleared</p>";

    echo "<h3>Step 3: Clearing config cache...</h3>";
    Artisan::call('config:clear');
    echo "<p style='color: green;'>✓ Config cache cleared</p>";

    echo "<h3>Step 4: Clearing route cache...</h3>";
    Artisan::call('route:clear');
    echo "<p style='color: green;'>✓ Route cache cleared</p>";

    echo "<hr>";
    echo "<p style='color: green; font-weight: bold;'>✓ All caches cleared successfully!</p>";
    echo "<p><strong>Next step:</strong> Refresh your page to see the changes.</p>";
    echo "<p><a href='/'>Go to Home</a> | <a href=''>Refresh This Page</a></p>";

} catch (\Exception $e) {
    echo "<p style='color: red;'><strong>✗ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

?>
