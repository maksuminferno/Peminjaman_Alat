<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    public function alat()
    {
        return $this->hasMany(Alat::class, 'id_kategori');
    }
}