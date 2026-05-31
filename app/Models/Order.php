<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id', 'product_id', 'quantity', 'total_price',
        'recipient_name', 'recipient_phone', 'address', 'city',
        'payment_method', 'status', 'notes', 'paid_at',
        // Legacy fields (backward compat)
        'produk_jadi_id', 'jumlah', 'total_harga_koin', 'alamat_kirim',
    ];

    protected $casts = [
        'total_price' => 'integer',
        'paid_at'     => 'datetime',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Legacy relation
    public function produkJadi()
    {
        return $this->belongsTo(ProdukJadi::class, 'produk_jadi_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => '⏳ Menunggu Pembayaran',
            'paid'       => '✅ Pembayaran Diterima',
            'processing' => '⚙️ Sedang Diproses',
            'shipped'    => '🚚 Dikirim',
            'done'       => '🎉 Selesai',
            'cancelled'  => '❌ Dibatalkan',
            default      => $this->status,
        };
    }

    public function getStatusBgAttribute(): string
    {
        return match($this->status) {
            'paid'       => '#dcfce7',
            'processing' => '#dbeafe',
            'shipped'    => '#ccfbf1',
            'done'       => '#f0fdf4',
            'cancelled'  => '#fee2e2',
            default      => '#fef9c3',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'paid'       => '#166534',
            'processing' => '#1d4ed8',
            'shipped'    => '#0f766e',
            'done'       => '#166534',
            'cancelled'  => '#991b1b',
            default      => '#a16207',
        };
    }
}