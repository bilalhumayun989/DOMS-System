<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripExpense extends Model
{
    protected $fillable = ['trip_id', 'expense_ref', 'category', 'amount', 'description', 'expense_date'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'expense_date' => 'date'];
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
