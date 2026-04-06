<?php
// Script to check and create database tables if they don't exist

// Connect to SQLite database
$db = new SQLite3('database/database.sqlite');

// Check if users table exists
$tables = [
    'users' => "
        CREATE TABLE users (
            id_user INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            nama TEXT,
            email TEXT,
            no_telp TEXT,
            foto_profil TEXT,
            role TEXT,
            created_at DATETIME,
            updated_at DATETIME
        )
    ",
    'kategori' => "
        CREATE TABLE kategori (
            id_kategori INTEGER PRIMARY KEY AUTOINCREMENT,
            nama_kategori TEXT NOT NULL,
            created_at DATETIME,
            updated_at DATETIME
        )
    ",
    'alat' => "
        CREATE TABLE alat (
            id_alat INTEGER PRIMARY KEY AUTOINCREMENT,
            nama_alat TEXT NOT NULL,
            stok INTEGER DEFAULT 0,
            id_kategori INTEGER,
            created_at DATETIME,
            updated_at DATETIME,
            FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE CASCADE
        )
    ",
    'peminjaman' => "
        CREATE TABLE peminjaman (
            id_peminjaman INTEGER PRIMARY KEY AUTOINCREMENT,
            tanggal_pinjam DATE NOT NULL,
            tanggal_kembali_rencana DATE NOT NULL,
            status TEXT NOT NULL,
            id_user INTEGER,
            created_at DATETIME,
            updated_at DATETIME,
            FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
        )
    ",
    'detail_peminjaman' => "
        CREATE TABLE detail_peminjaman (
            id_detail INTEGER PRIMARY KEY AUTOINCREMENT,
            id_peminjaman INTEGER,
            id_alat INTEGER,
            jumlah INTEGER DEFAULT 1,
            created_at DATETIME,
            updated_at DATETIME,
            FOREIGN KEY (id_peminjaman) REFERENCES peminjaman(id_peminjaman) ON DELETE CASCADE,
            FOREIGN KEY (id_alat) REFERENCES alat(id_alat) ON DELETE CASCADE
        )
    ",
    'pengembalian' => "
        CREATE TABLE pengembalian (
            id_pengembalian INTEGER PRIMARY KEY AUTOINCREMENT,
            tanggal_kembali DATE NOT NULL,
            denda INTEGER DEFAULT 0,
            id_peminjaman INTEGER,
            created_at DATETIME,
            updated_at DATETIME,
            FOREIGN KEY (id_peminjaman) REFERENCES peminjaman(id_peminjaman) ON DELETE CASCADE
        )
    "
];

// Check and create each table if it doesn't exist
foreach ($tables as $tableName => $createQuery) {
    $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$tableName'");
    if (!$result->fetchArray()) {
        echo "Creating table $tableName...\n";
        $db->exec($createQuery);
        echo "Table $tableName created successfully.\n\n";
    } else {
        echo "Table $tableName already exists.\n\n";
    }
}

// Close the database connection
$db->close();

echo "Database setup completed!\n";
?>