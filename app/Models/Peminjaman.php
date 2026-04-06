<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    
    protected $fillable = [
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'status',
        'id_user',
        'disetujui_oleh',
        'alasan_ditolak',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh', 'id_user');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'id_peminjaman');
    }
}