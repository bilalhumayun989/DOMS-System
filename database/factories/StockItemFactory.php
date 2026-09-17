<?php

namespace Database\Factories;

use App\Models\StockItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockItem>
 */
class StockItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku_code' => fake()->unique()->bothify('SKU-#####'), 'product_name' => fake()->words(3, true), 'category' => 'Beverages', 'current_stock' => 100, 'reorder_point' => 20,
        ];
    }
}
