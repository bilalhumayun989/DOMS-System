<?php

namespace App\Models;

use Database\Factories\ExpenseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /** @use HasFactory<ExpenseFactory> */
    use HasFactory;

    protected $fillable = ['expense_id', 'date', 'category', 'source', 'driver', 'route', 'amount', 'voucher', 'status', 'approved_by', 'payment_source', 'receipt', 'market', 'created_by', 'notes', 'trip_id', 'attachment_path'];

    protected function casts(): array
    {
        return ['amount' => 'float'];
    }
}
