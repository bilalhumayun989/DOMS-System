<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripSettlement extends Model
{
    protected $fillable = ['trip_id', 'expected_cash', 'collected_amount', 'expense_amount', 'difference_amount', 'shortage_classification', 'notes', 'settled_at'];

    protected function casts(): array
    {
        return ['expected_cash' => 'decimal:2', 'collected_amount' => 'decimal:2', 'expense_amount' => 'decimal:2', 'difference_amount' => 'decimal:2', 'settled_at' => 'datetime'];
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
