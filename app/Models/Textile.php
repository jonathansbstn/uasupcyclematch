<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Textile extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'user_id',
        'title',
        'fabric_type',
        'description',
        'weight',
        'address',
        'image',
        'latitude',
        'longitude',
        'status',
        'claimed_by',
        'claimed_at',
        'product_image',
    ];
 
    protected $casts = [
        'weight'     => 'decimal:2',
        'latitude'   => 'decimal:8',
        'longitude'  => 'decimal:8',
        'claimed_at' => 'datetime',
    ];
 
    // Relasi ke pemilik limbah (contributor)
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
 
    // Relasi ke penjahit yang mengklaim (upcycler)
    public function upcycler()
    {
        return $this->belongsTo(User::class, 'claimed_by');
    }

    // Relasi ke klaim
    public function wasteClaim()
    {
        return $this->hasOne(WasteClaim::class);
    }

    // Relasi ke produk
    public function product()
    {
        return $this->hasOne(Product::class);
    }
 
    // Scope untuk filter status
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
 
    public function scopeClaimed($query)
    {
        return $query->where('status', 'claimed');
    }
 
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
 
    // Label badge status
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available'  => '🟢 Tersedia',
            'claimed'    => '🟡 Diklaim',
            'processing' => '🔵 Diproses',
            'completed'  => '✅ Selesai',
            default      => $this->status,
        };
    }
}