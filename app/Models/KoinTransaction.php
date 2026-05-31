<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KoinTransaction extends Model
{
    use HasFactory;

    // Mengizinkan kolom ini diisi oleh backend secara massal
    protected $fillable = [
        'user_id', 
        'amount', 
        'type', 
        'keterangan', 
        'metode', 
        'no_rekening'
    ];

    // Relasi untuk mengetahui transaksi ini milik siapa
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}