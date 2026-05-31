<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'upcycler_id',
        'textile_id',
        'name',
        'product_name',
        'category',
        'description',
        'price',
        'photo',
        'status', // pending, published, rejected
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    public function upcycler()
    {
        return $this->belongsTo(User::class, 'upcycler_id');
    }

    // alias: owner = upcycler (dipakai di admin galeri)
    public function owner()
    {
        return $this->belongsTo(User::class, 'upcycler_id');
    }

    public function textile()
    {
        return $this->belongsTo(Textile::class);
    }

    // Label status
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'published' => '✅ Published',
            'rejected'  => '❌ Ditolak',
            default     => '⏳ Pending Review',
        };
    }

    // Nama tampilan (prioritas: name, fallback: product_name)
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?? $this->product_name ?? 'Tanpa Nama';
    }
}
