<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        foreach ($categories as $category) {
            Kategori::create($category);
        }
    }
}