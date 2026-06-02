<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KoinWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'jumlah_koin', 'nominal_rupiah',
        'metode', 'nama_bank', 'nomor_rekening', 'nama_penerima',
        'status', 'catatan_admin', 'processed_at',
    ];

    protected $casts = [
        'jumlah_koin'   => 'integer',
        'nominal_rupiah'=> 'integer',
        'processed_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMetodeLabelAttribute(): string
    {
        return $this->metode === 'bank' ? 'Transfer Bank' : 'E-Wallet';
    }

    public function getStatusLabelAttribute(): string
    {
        return [
            'pending'  => '⏳ Menunggu Proses',
            'approved' => '✅ Disetujui',
            'rejected' => '❌ Ditolak',
        ][$this->status] ?? $this->status;
    }
}
