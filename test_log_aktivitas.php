<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "=== Testing Log Aktivitas ===\n\n";

// Check if table exists
try {
    $count = LogAktivitas::count();
    echo "✓ Log aktivitas table exists\n";
    echo "  Current records: $count\n\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Get first user
$user = User::first();
if (!$user) {
    echo "✗ No users found in database\n";
    exit(1);
}

echo "✓ Found user: {$user->nama} (ID: {$user->id_user})\n\n";

// Create test log entries
echo "Creating test log entries...\n";
for ($i = 1; $i <= 5; $i++) {
    $log = LogAktivitas::create([
        'id_user' => $user->id_user,
        'aktivitas' => "Test aktivitas #$i - Login ke sistem",
        'waktu' => now()->subMinutes($i * 10),
    ]);
    echo "  ✓ Created log #$i: {$log->aktivitas}\n";
}

echo "\n✓ Successfully created 5 test log entries\n";
echo "  Total logs now: " . LogAktivitas::count() . "\n\n";

echo "=== Test Complete ===\n";
echo "Refresh the Log Aktivitas page to see the test data!\n";
