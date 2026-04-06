<?php
// Migration runner script
// Run this script to execute pending migrations

require_once __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Run the pending migrations
    Artisan::call('migrate', [
        '--force' => true // This option is needed to run in production-like environments
    ]);
    
    echo "Migrations completed successfully!\n";
    echo Artisan::output();
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}