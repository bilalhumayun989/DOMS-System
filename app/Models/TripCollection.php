<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripCollection extends Model
{
    protected $fillable = ['trip_id', 'collection_ref', 'customer', 'invoice_number', 'amount', 'method', 'cheque_number', 'bank_name', 'instrument_date', 'bank_reference', 'notes', 'collected_at'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'instrument_date' => 'date', 'collected_at' => 'datetime'];
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
