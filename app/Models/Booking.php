<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\BookingCheckin;

class Booking extends Model
{
    use HasFactory;

    public const TYPE_DAILY = 'daily';
    public const TYPE_MONTHLY = 'monthly';

    public const STATUS_PENDING = 'PENDING';
    public const STATUS_PAID = 'PAID';
    public const STATUS_CANCELED = 'CANCELED';
    public const STATUS_EXPIRED = 'EXPIRED';

    protected $fillable = [
        'user_id',
        'branch_id',
        'type',
        'start_date',
        'end_date',
        'amount',
        'status',
        'booking_code',
        'paid_at',
        'checked_in_at',
        'checked_in_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'checked_in_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(BookingCheckin::class);
    }
}

