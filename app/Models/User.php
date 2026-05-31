<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'whatsapp', 'password', 'role', 'koin', 'is_verified'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Biar password otomatis ter-hash dengan aman di Laravel terbaru
        'koin' => 'integer',
        'is_verified' => 'boolean', // <== Tambahan cast untuk flag verifikasi upcycler
    ];

    // RELASI KHUSUS CONTRIBUTOR
    public function limbahKains() 
    { 
        return $this->hasMany(LimbahKain::class, 'user_id'); 
    }

    // RELASI KHUSUS UPCYCLER / PENJAHIT (Tambahkan ini agar sinkron dengan penjahit_id)
    public function tugasJahit()
    {
        return $this->hasMany(LimbahKain::class, 'penjahit_id');
    }

    // RELASI PRODUK YANG DIBUAT OLEH PENJAHIT (Tambahkan ini untuk fitur Eco-Mall nanti)
    public function produkJadis()
    {
        return $this->hasMany(ProdukJadi::class, 'penjahit_id');
    }

    // RELASI KEUANGAN & TRANSAKSI KOIN
    public function koinTransactions() 
    { 
        return $this->hasMany(KoinTransaction::class, 'user_id'); 
    }

    // RELASI PEMBELIAN PRODUK (ORDER)
    public function orders() 
    { 
        return $this->hasMany(Order::class, 'buyer_id'); 
    }

    // HELPER CEK ROLE USER
    public function isContributor() { return $this->role === 'contributor'; }
    public function isUpcycler()    { return $this->role === 'upcycler'; }
    public function isAdmin()       { return $this->role === 'admin'; }
}