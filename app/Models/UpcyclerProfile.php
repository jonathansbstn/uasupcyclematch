<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpcyclerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ktp_photo',
        'address',
        'verification_status',
        'rejection_reason',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
