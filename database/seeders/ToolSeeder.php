<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alat;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        foreach ($tools as $tool) {
            Alat::create($tool);
        }
    }
}