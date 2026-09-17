<?php

namespace Database\Factories;

use App\Models\Market;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Market>
 */
class MarketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(), 'area' => fake()->city(), 'contact' => fake()->name(), 'phone' => fake()->phoneNumber(), 'outstanding_balance' => 0,
        ];
    }
}
