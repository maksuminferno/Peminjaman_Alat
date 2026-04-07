<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'username' => 'admin',
            'nama' => 'Administrator',
            'email' => 'admin@peminjaman.com',
            'password' => Hash::make('Admin123!'),
            'role' => 'admin',
            'no_telp' => '081234567890',
        ]);

        // Petugas User 1
        User::create([
            'username' => 'petugas1',
            'nama' => 'Petugas 1 - Peminjaman',
            'email' => 'petugas1@peminjaman.com',
            'password' => Hash::make('Petugas123!'),
            'role' => 'petugas',
            'no_telp' => '081234567891',
        ]);

        // Petugas User 2
        User::create([
            'username' => 'petugas2',
            'nama' => 'Petugas 2 - Pengembalian',
            'email' => 'petugas2@peminjaman.com',
            'password' => Hash::make('Petugas123!'),
            'role' => 'petugas',
            'no_telp' => '081234567892',
        ]);

        // Peminjam User 1
        User::create([
            'username' => 'johndoe',
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('User123!'),
            'role' => 'peminjam',
            'no_telp' => '081234567893',
        ]);

        // Peminjam User 2
        User::create([
            'username' => 'janesmith',
            'nama' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('User123!'),
            'role' => 'peminjam',
            'no_telp' => '081234567894',
        ]);

        // Peminjam User 3
        User::create([
            'username' => 'ahmadrizki',
            'nama' => 'Ahmad Rizki',
            'email' => 'ahmad@example.com',
            'password' => Hash::make('User123!'),
            'role' => 'peminjam',
            'no_telp' => '081234567895',
        ]);

        $this->command->info('✅ Users created successfully!');
        $this->command->info('');
        $this->command->info('Default Users:');
        $this->command->info('┌─────────────┬────────────────────────────┬──────────────────┬──────────────┐');
        $this->command->info('│ Role        │ Email                      │ Username         │ Password     │');
        $this->command->info('├─────────────┼────────────────────────────┼──────────────────┼──────────────┤');
        $this->command->info('│ Admin       │ admin@peminjaman.com       │ admin            │ Admin123!    │');
        $this->command->info('│ Petugas 1   │ petugas1@peminjaman.com    │ petugas1         │ Petugas123!  │');
        $this->command->info('│ Petugas 2   │ petugas2@peminjaman.com    │ petugas2         │ Petugas123!  │');
        $this->command->info('│ Peminjam 1  │ john@example.com           │ johndoe          │ User123!     │');
        $this->command->info('│ Peminjam 2  │ jane@example.com           │ janesmith        │ User123!     │');
        $this->command->info('│ Peminjam 3  │ ahmad@example.com          │ ahmadrizki       │ User123!     │');
        $this->command->info('└─────────────┴────────────────────────────┴──────────────────┴──────────────┘');
    }
}
