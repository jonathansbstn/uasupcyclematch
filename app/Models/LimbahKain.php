<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LimbahKain extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'judul', 'jenis_bahan', 'berat_kg',
        'deskripsi', 'latitude', 'longitude', 'status', 'penjahit_id'
    ];

    // Mengambil data Kontributor pemilik kain
    public function contributor() 
    { 
        return $this->belongsTo(User::class, 'user_id'); 
    }

    // Mengambil data Penjahit yang mengambil/mengklaim kain
    public function penjahit() 
    { 
        return $this->belongsTo(User::class, 'penjahit_id'); 
    }

    // Menghubungkan ke produk jadi hasil olahan kain ini
    public function produkJadi() 
    { 
        return $this->hasOne(ProdukJadi::class, 'limbah_id'); 
    }
}