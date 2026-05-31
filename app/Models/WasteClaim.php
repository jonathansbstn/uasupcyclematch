<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'textile_id',
        'upcycler_id',
        'claimed_at',
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
    ];

    public function textile()
    {
        return $this->belongsTo(Textile::class);
    }

    public function upcycler()
    {
        return $this->belongsTo(User::class, 'upcycler_id');
    }
}
