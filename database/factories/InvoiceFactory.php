<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Market;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_number' => fake()->unique()->bothify('INV-#####'), 'customer' => fake()->company(), 'trip_id' => Trip::factory(), 'market_id' => Market::factory(), 'date' => '2026-09-01', 'total_value' => 5000, 'status' => 'NOT DELIVERED',
        ];
    }
}
