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
        $users = [
            [
                'username' => 'peminjam1',
                'password' => Hash::make('password'),
                'nama' => 'Ahmad Fauzi',
                'email' => 'peminjam1@example.com',
                'no_telp' => '081234567890',
                'role' => 'peminjam',
            ],
            [
                'username' => 'peminjam2',
                'password' => Hash::make('password'),
                'nama' => 'Siti Nurhaliza',
                'email' => 'peminjam2@example.com',
                'no_telp' => '081234567891',
                'role' => 'peminjam',
            ],
            [
                'username' => 'admin',
                'password' => Hash::make('password'),
                'nama' => 'Admin System',
                'email' => 'admin@example.com',
                'no_telp' => '081234567899',
                'role' => 'admin',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}