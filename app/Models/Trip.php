<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Trip extends Model
{
    public const STATUSES = ['DRAFT', 'READY', 'DISPATCHED', 'COMPLETED', 'SETTLEMENT PENDING', 'SETTLED', 'CLOSED'];

    protected $fillable = ['trip_number', 'trip_date', 'deliveryman_id', 'deliveryman_name', 'vehicle', 'market_area', 'source_dlf', 'status', 'delivery_result', 'follow_up_date', 'delivery_notes', 'load_value', 'expected_cash', 'closed_at'];

    protected function casts(): array
    {
        return ['trip_date' => 'date', 'follow_up_date' => 'date', 'closed_at' => 'datetime', 'load_value' => 'decimal:2', 'expected_cash' => 'decimal:2'];
    }

    public function collections(): HasMany
    {
        return $this->hasMany(TripCollection::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(TripExpense::class);
    }

    public function settlement(): HasOne
    {
        return $this->hasOne(TripSettlement::class);
    }

    public function isClosed(): bool
    {
        return $this->status === 'CLOSED';
    }
}
