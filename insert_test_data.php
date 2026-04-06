<?php
// Simple script to insert test users into the database

// Connect to SQLite database
$db = new SQLite3('database/database.sqlite');

// Hash the password
$password = password_hash('password', PASSWORD_DEFAULT);

// Insert test users
$users = [
    [
        'username' => 'peminjam1',
        'password' => $password,
        'nama' => 'Ahmad Fauzi',
        'email' => 'peminjam1@example.com',
        'no_telp' => '081234567890',
        'role' => 'peminjam',
    ],
    [
        'username' => 'peminjam2',
        'password' => $password,
        'nama' => 'Siti Nurhaliza',
        'email' => 'peminjam2@example.com',
        'no_telp' => '081234567891',
        'role' => 'peminjam',
    ],
    [
        'username' => 'admin',
        'password' => $password,
        'nama' => 'Admin System',
        'email' => 'admin@example.com',
        'no_telp' => '081234567899',
        'role' => 'admin',
    ],
];

// Clear existing users first (optional)
$db->exec("DELETE FROM users WHERE username IN ('peminjam1', 'peminjam2', 'admin')");

// Insert the test users
$stmt = $db->prepare("INSERT INTO users (username, password, nama, email, no_telp, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, datetime('now'), datetime('now'))");

foreach ($users as $user) {
    $stmt->bindValue(1, $user['username'], SQLITE3_TEXT);
    $stmt->bindValue(2, $user['password'], SQLITE3_TEXT);
    $stmt->bindValue(3, $user['nama'], SQLITE3_TEXT);
    $stmt->bindValue(4, $user['email'], SQLITE3_TEXT);
    $stmt->bindValue(5, $user['no_telp'], SQLITE3_TEXT);
    $stmt->bindValue(6, $user['role'], SQLITE3_TEXT);
    $result = $stmt->execute();
    
    if ($result) {
        echo "User {$user['username']} inserted successfully.\n";
    } else {
        echo "Error inserting user {$user['username']}.\n";
    }
}

// Insert test categories
$categories = [
    ['nama_kategori' => 'Kamera'],
    ['nama_kategori' => 'Drone'],
    ['nama_kategori' => 'Aksesoris'],
    ['nama_kategori' => 'Audio'],
    ['nama_kategori' => 'Pencahayaan'],
    ['nama_kategori' => 'Monitor'],
    ['nama_kategori' => 'Statif'],
    ['nama_kategori' => 'Lensa'],
];

$db->exec("DELETE FROM kategori WHERE nama_kategori IN ('Kamera', 'Drone', 'Aksesoris', 'Audio', 'Pencahayaan', 'Monitor', 'Statif', 'Lensa')");

$stmt = $db->prepare("INSERT INTO kategori (nama_kategori, created_at, updated_at) VALUES (?, datetime('now'), datetime('now'))");

foreach ($categories as $category) {
    $stmt->bindValue(1, $category['nama_kategori'], SQLITE3_TEXT);
    $result = $stmt->execute();
    
    if ($result) {
        echo "Category {$category['nama_kategori']} inserted successfully.\n";
    } else {
        echo "Error inserting category {$category['nama_kategori']}.\n";
    }
}

// Insert test tools
$tools = [
    [
        'nama_alat' => 'Canon EOS R6',
        'stok' => 5,
        'id_kategori' => 1, // Kamera
    ],
    [
        'nama_alat' => 'DJI Mavic 3',
        'stok' => 2,
        'id_kategori' => 2, // Drone
    ],
    [
        'nama_alat' => 'Ronin-S Stabilizer',
        'stok' => 3,
        'id_kategori' => 3, // Aksesoris
    ],
    [
        'nama_alat' => 'Zoom H6 Recorder',
        'stok' => 0,
        'id_kategori' => 4, // Audio
    ],
    [
        'nama_alat' => 'RGB LED Panel 300W',
        'stok' => 4,
        'id_kategori' => 5, // Pencahayaan
    ],
    [
        'nama_alat' => 'Field Monitor 7"',
        'stok' => 6,
        'id_kategori' => 6, // Monitor
    ],
    [
        'nama_alat' => 'Carbon Fiber Tripod',
        'stok' => 0,
        'id_kategori' => 7, // Statif
    ],
    [
        'nama_alat' => '24-70mm f/2.8 Lens',
        'stok' => 2,
        'id_kategori' => 8, // Lensa
    ],
];

$db->exec("DELETE FROM alat WHERE nama_alat IN ('Canon EOS R6', 'DJI Mavic 3', 'Ronin-S Stabilizer', 'Zoom H6 Recorder', 'RGB LED Panel 300W', 'Field Monitor 7\"', 'Carbon Fiber Tripod', '24-70mm f/2.8 Lens')");

$stmt = $db->prepare("INSERT INTO alat (nama_alat, stok, id_kategori, created_at, updated_at) VALUES (?, ?, ?, datetime('now'), datetime('now'))");

foreach ($tools as $tool) {
    $stmt->bindValue(1, $tool['nama_alat'], SQLITE3_TEXT);
    $stmt->bindValue(2, $tool['stok'], SQLITE3_INTEGER);
    $stmt->bindValue(3, $tool['id_kategori'], SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    if ($result) {
        echo "Tool {$tool['nama_alat']} inserted successfully.\n";
    } else {
        echo "Error inserting tool {$tool['nama_alat']}.\n";
    }
}

echo "\nDatabase seeding completed successfully!\n";
echo "Test accounts created:\n";
echo "- Username: peminjam1, Password: password\n";
echo "- Username: peminjam2, Password: password\n";
echo "- Username: admin, Password: password\n";

$db->close();
?>