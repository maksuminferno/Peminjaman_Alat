<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== Debug Log Aktivitas ===\n\n";

// 1. Check if table exists
echo "1. Checking database tables...\n";
try {
    $tables = DB::select("SHOW TABLES");
    $tableNames = array_map(function($t) { return array_values((array)$t)[0]; }, $tables);
    echo "   Tables found: " . count($tableNames) . "\n";
    
    if (in_array('log_aktivitas', $tableNames)) {
        echo "   ✓ log_aktivitas table EXISTS\n";
    } else {
        echo "   ✗ log_aktivitas table NOT FOUND!\n";
        echo "   Running migration...\n";
        exit(1);
    }
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Check current auth user
echo "\n2. Checking authenticated user...\n";
echo "   Note: Running in CLI, no user is authenticated\n";
echo "   Will use first available user for testing\n";

$firstUser = \App\Models\User::first();
if (!$firstUser) {
    echo "   ✗ No users found in database!\n";
    exit(1);
}
echo "   ✓ Using user: {$firstUser->nama} (ID: {$firstUser->id_user})\n";

// 3. Check LogAktivitas model
echo "\n3. Testing LogAktivitas model...\n";
try {
    $count = \App\Models\LogAktivitas::count();
    echo "   ✓ Current logs: {$count}\n";
    
    // Create test log
    $testLog = \App\Models\LogAktivitas::create([
        'id_user' => $firstUser->id_user,
        'aktivitas' => 'TEST - Log aktivitas manual dari CLI',
        'waktu' => now(),
    ]);
    echo "   ✓ Test log created with ID: {$testLog->id_log}\n";
    
    $newCount = \App\Models\LogAktivitas::count();
    echo "   ✓ Total logs now: {$newCount}\n";
    
} catch (\Exception $e) {
    echo "   ✗ Error creating log: " . $e->getMessage() . "\n";
    echo "   Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

// 4. Check foreign key constraint
echo "\n4. Checking foreign key constraints...\n";
try {
    $constraints = DB::select("
        SELECT 
            TABLE_NAME,
            COLUMN_NAME,
            CONSTRAINT_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_NAME = 'log_aktivitas'
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    if (empty($constraints)) {
        echo "   ⚠ No foreign key constraints found (this is OK if data exists)\n";
    } else {
        foreach ($constraints as $constraint) {
            echo "   ✓ FK: {$constraint->COLUMN_NAME} -> {$constraint->REFERENCED_TABLE_NAME}.{$constraint->REFERENCED_COLUMN_NAME}\n";
        }
    }
} catch (\Exception $e) {
    echo "   ⚠ Could not check constraints: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
echo "Check your Log Aktivitas page - you should see 1 test entry!\n";
