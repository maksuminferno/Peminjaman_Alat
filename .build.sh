#!/bin/sh

# Buat folder bootstrap/cache jika belum ada
mkdir -p bootstrap/cache
chmod -R 775 bootstrap/cache

# Buat folder storage agar Laravel bisa menulis cache & logs
mkdir -p storage/framework/cache/data
chmod -R 775 storage