<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'open_hours',
        'daily_price',
        'monthly_price',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'daily_price' => 'decimal:2',
        'monthly_price' => 'decimal:2',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}

