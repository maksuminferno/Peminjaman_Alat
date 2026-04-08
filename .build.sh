#!/bin/sh

# Buat folder bootstrap/cache jika belum ada
mkdir -p bootstrap/cache

# Berikan permission menulis
chmod -R 775 bootstrap/cache

# Buat folder storage jika belum ada
mkdir -p storage/framework/cache/data
chmod -R 775 storage