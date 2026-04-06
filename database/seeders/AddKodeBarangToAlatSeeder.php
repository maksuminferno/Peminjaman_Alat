<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddKodeBarangToAlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $alatList = DB::table('alat')->get();
        
        foreach ($alatList as $index => $alat) {
            if (empty($alat->kode_barang)) {
                // Generate kode_barang based on category and index
                $kategori = DB::table('kategori')->where('id_kategori', $alat->id_kategori)->first();
                $kategoriPrefix = $kategori ? strtoupper(substr($kategori->nama_kategori, 0, 3)) : 'ALT';
                $kodeBarang = $kategoriPrefix . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                
                DB::table('alat')
                    ->where('id_alat', $alat->id_alat)
                    ->update(['kode_barang' => $kodeBarang]);
            }
        }
        
        $this->command->info('Kode barang berhasil ditambahkan ke semua alat!');
    }
}
