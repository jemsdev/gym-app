<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingCheckin extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'checked_in_at',
        'checked_in_by',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}

