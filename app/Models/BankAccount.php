<?php

namespace App\Models;

use Database\Factories\BankAccountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends Model
{
    /** @use HasFactory<BankAccountFactory> */
    use HasFactory;

    protected $fillable = ['bank', 'account', 'opening', 'opening_date', 'type', 'branch', 'status', 'notes'];

    protected function casts(): array
    {
        return ['opening' => 'float', 'opening_date' => 'date'];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class, 'bank_id');
    }
}
