<?php
// Simple seeder execution script

require_once __DIR__.'/vendor/autoload.php';

// Create the application
$app = require_once __DIR__.'/bootstrap/app.php';

// Set the application instance
$app->bind('app', function () use ($app) {
    return $app;
});

// Initialize Laravel
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Artisan;

try {
    // Run the seeders
    Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    echo "Database seeding completed successfully!\n";
    echo "Test accounts created:\n";
    echo "- Username: peminjam1, Password: password\n";
    echo "- Username: peminjam2, Password: password\n";
    echo "- Username: admin, Password: password\n";
} catch (Exception $e) {
    echo "Error during seeding: " . $e->getMessage() . "\n";
}