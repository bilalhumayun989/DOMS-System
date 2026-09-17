<?php

namespace Database\Factories;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trip_number' => fake()->unique()->bothify('TR-TEST-#####'), 'trip_date' => '2026-09-01',
            'deliveryman_name' => 'Test Driver', 'vehicle' => 'KHI-1234', 'market_area' => 'Test Market',
            'status' => 'DRAFT', 'load_value' => 10000, 'expected_cash' => 10000,
        ];
    }
}
