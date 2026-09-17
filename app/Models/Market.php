<?php

namespace App\Models;

use Database\Factories\MarketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    /** @use HasFactory<MarketFactory> */
    use HasFactory;

    protected $fillable = ['name', 'area', 'contact', 'phone', 'outstanding_balance'];

    protected function casts(): array
    {
        return ['outstanding_balance' => 'float'];
    }
}
