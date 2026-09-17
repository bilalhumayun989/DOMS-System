<?php

namespace App\Models;

use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /** @use HasFactory<InvoiceFactory> */
    use HasFactory;

    protected $fillable = ['invoice_number', 'customer', 'market_id', 'trip_id', 'date', 'total_value', 'status'];

    protected function casts(): array
    {
        return ['total_value' => 'float'];
    }
}
