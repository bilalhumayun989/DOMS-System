<?php

namespace Database\Factories;

use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BankAccount>
 */
class BankAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bank' => fake()->company(), 'account' => fake()->unique()->iban(), 'opening' => 10000, 'opening_date' => '2026-01-01', 'type' => 'Business Current', 'branch' => fake()->city(), 'status' => 'Active',
        ];
    }
}
