<?php

namespace App\Models;

use Database\Factories\ReturnClaimFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnClaim extends Model
{
    /** @use HasFactory<ReturnClaimFactory> */
    use HasFactory;

    protected $fillable = ['return_ref', 'trip_display', 'invoice_ref', 'shop', 'market', 'deliveryman', 'distributor', 'return_type', 'units', 'status', 'main_reason', 'condition', 'credit_note', 'impact', 'claim_status', 'trip_id', 'date', 'value', 'remarks', 'items'];

    protected function casts(): array
    {
        return ['value' => 'float', 'items' => 'array'];
    }
}
