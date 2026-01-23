<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'daily_price',
        'monthly_price',
        'is_active',
    ];

    protected $casts = [
        'daily_price' => 'decimal:2',
        'monthly_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}

