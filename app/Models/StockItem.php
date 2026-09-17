<?php

namespace App\Models;

use Database\Factories\StockItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    /** @use HasFactory<StockItemFactory> */
    use HasFactory;

    protected $fillable = ['sku_code', 'product_name', 'category', 'current_stock', 'reorder_point'];

    protected function casts(): array
    {
        return ['current_stock' => 'integer', 'reorder_point' => 'integer'];
    }
}
