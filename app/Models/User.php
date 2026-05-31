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
        'password'          => 'hashed',
        'koin'              => 'integer',
        'is_verified'       => 'boolean',
    ];

    // RELASI KHUSUS CONTRIBUTOR
    public function limbahKains()
    {
        return $this->hasMany(LimbahKain::class, 'user_id');
    }

    // Textile postings milik contributor ini
    public function textiles()
    {
        return $this->hasMany(Textile::class, 'user_id');
    }

    // RELASI KHUSUS UPCYCLER - textile yang diklaim
    public function tugasJahit()
    {
        return $this->hasMany(LimbahKain::class, 'penjahit_id');
    }

    // Textile yang diklaim oleh upcycler ini
    public function claimedTextiles()
    {
        return $this->hasMany(Textile::class, 'claimed_by');
    }

    // Waste claims oleh user ini
    public function wasteClaims()
    {
        return $this->hasMany(WasteClaim::class, 'upcycler_id');
    }

    // Produk yang dibuat
    public function products()
    {
        return $this->hasMany(Product::class, 'upcycler_id');
    }

    // Profil upcycler
    public function upcyclerProfile()
    {
        return $this->hasOne(UpcyclerProfile::class);
    }

    // RELASI PRODUK YANG DIBUAT OLEH PENJAHIT
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