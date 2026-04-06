<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah akun petugas sudah ada
        $existingPetugas = User::where('username', 'petugas2')->first();
        
        if (!$existingPetugas) {
            User::create([
                'nama' => 'Petugas Utama',
                'username' => 'petugas2',
                'email' => 'petugas@example.com',
                'no_telp' => '081234567890',
                'role' => 'petugas',
                'password' => Hash::make('password123'),
            ]);
            
            echo "Akun petugas berhasil dibuat:\n";
            echo "Username: petugas1\n";
            echo "Password: password123\n";
            echo "Role: petugas\n";
        } else {
            echo "Akun petugas sudah ada.\n";
        }
    }
}