<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukJadi extends Model
{
    use HasFactory;

    // Nama tabel disesuaikan dengan plural standard Laravel jika tidak diatur manual
    protected $table = 'produk_jadis';

    protected $fillable = [
        'limbah_id', 'penjahit_id', 'nama_produk', 'deskripsi', 'harga_koin', 'foto', 'stok'
    ];

    // Relasi balik ke Penjahit yang membuat produk
    public function penjahit()
    {
        return $this->belongsTo(User::class, 'penjahit_id');
    }

    // Relasi ke bahan baku LimbahKain yang digunakan
    public function limbahKain()
    {
        return $this->belongsTo(LimbahKain::class, 'limbah_id');
    }
}