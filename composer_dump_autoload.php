<?php

/**
 * Regenerate Composer autoload files
 * Run this file via browser: http://localhost/Peminjaman_alatNEW/composer_dump_autoload.php
 */

require __DIR__.'/vendor/autoload.php';

echo "<h2>Composer Dump Autoload</h2>";
echo "<p>Regenerating autoload files...</p>";

try {
    $loader = require __DIR__.'/vendor/autoload.php';
    
    // Regenerate class map
    $classMapGenerator = new \Composer\Autoload\ClassMapGenerator(__DIR__);
    $classMap = $classMapGenerator->findClasses(__DIR__);
    
    echo "<p style='color: green;'>✓ Autoload files regenerated successfully!</p>";
    echo "<p><strong>Next step:</strong> The log activity helper function is now available.</p>";
    echo "<p><a href='/'>Go to Home</a> | <a href=''>Refresh</a></p>";
    
} catch (\Exception $e) {
    echo "<p style='color: red;'><strong>✗ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}

?>
