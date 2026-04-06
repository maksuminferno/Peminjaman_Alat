<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';
    protected $primaryKey = 'id_pengembalian';

    protected $fillable = [
        'tanggal_kembali',
        'denda',
        'denda_keterlambatan',
        'denda_kerusakan',
        'kondisi_alat',
        'deskripsi_kerusakan',
        'bukti_foto',
        'id_peminjaman',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }
}