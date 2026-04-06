<?php

/**
 * Test route log_aktivitas
 * Run this file via browser to test the route
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Route;

echo "<h2>Test Route: admin.log_aktivitas</h2>";
echo "<p>Checking if route exists...</p>";

try {
    // Check if route exists
    $route = Route::getRoutes()->getByName('admin.log_aktivitas');
    
    if ($route) {
        echo "<p style='color: green;'>✓ Route <strong>admin.log_aktivitas</strong> exists!</p>";
        echo "<p><strong>URI:</strong> " . $route->uri() . "</p>";
        echo "<p><strong>Method:</strong> " . implode(', ', $route->methods()) . "</p>";
        echo "<p><strong>Action:</strong> " . $route->getActionName() . "</p>";
        echo "<hr>";
        echo "<p><a href='/admin/log-aktivitas'>Go to Log Aktivitas Page</a></p>";
    } else {
        echo "<p style='color: red;'>✗ Route <strong>admin.log_aktivitas</strong> NOT found!</p>";
        
        // List all admin routes
        echo "<h3>All admin routes:</h3>";
        echo "<ul>";
        foreach (Route::getRoutes() as $r) {
            if (strpos($r->getName(), 'admin.') === 0) {
                echo "<li><strong>" . $r->getName() . "</strong> => " . $r->uri() . "</li>";
            }
        }
        echo "</ul>";
    }
    
} catch (\Exception $e) {
    echo "<p style='color: red;'><strong>✗ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}

?>
