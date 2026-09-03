<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ledger extends Model
{
    protected $fillable = [
        'ledger_group', 'entity_name', 'deliveryman_id', 'entry_date', 'voucher_reference',
        'transaction_category', 'entry_type', 'amount', 'previous_balance', 'running_balance',
        'payment_method', 'bank_reference', 'linked_invoice_trip', 'remarks', 'document_path',
        'verification_status', 'created_by',
    ];

    protected function casts(): array
    {
        return ['entry_date' => 'date', 'amount' => 'decimal:2', 'previous_balance' => 'decimal:2', 'running_balance' => 'decimal:2'];
    }

    public function deliveryman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deliveryman_id');
    }
}
