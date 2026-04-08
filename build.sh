#!/bin/bash
# Windows-friendly build script

set -e
cd "$(dirname "$0")"

echo "=== Clearing caches ==="
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

echo "=== Composer autoload ==="
composer install --no-dev --optimize-autoloader

echo "=== Laravel optimize ==="
php artisan optimize

echo "=== Build finished ==="