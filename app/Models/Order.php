<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id', 'produk_jadi_id', 'jumlah', 'total_harga_koin', 'status', 'alamat_kirim'
    ];

    // Relasi untuk mengetahui siapa pembeli produk ini
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // Relasi untuk mengetahui produk apa yang dibeli
    public function produkJadi()
    {
        return $this->belongsTo(ProdukJadi::class, 'produk_jadi_id');
    }
}