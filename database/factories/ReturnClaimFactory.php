<?php

namespace Database\Factories;

use App\Models\ReturnClaim;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReturnClaim>
 */
class ReturnClaimFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'return_ref' => fake()->unique()->bothify('RET-#####'), 'date' => '2026-09-01', 'value' => 100, 'items' => [],
        ];
    }
}
