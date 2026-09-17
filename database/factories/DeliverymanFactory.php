<?php

namespace Database\Factories;

use App\Models\Deliveryman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Deliveryman>
 */
class DeliverymanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(), 'employee_id' => fake()->unique()->bothify('EMP-#####'), 'phone' => fake()->phoneNumber(), 'joined_at' => '2026-01-01', 'vehicle' => 'KHI-1234', 'assigned_areas' => [],
        ];
    }
}
