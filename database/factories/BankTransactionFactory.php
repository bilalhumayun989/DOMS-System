<?php

namespace Database\Factories;

use App\Models\BankAccount;
use App\Models\BankTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BankTransaction>
 */
class BankTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bank_id' => BankAccount::factory(), 'date' => '2026-09-01', 'type' => 'Deposit / Credit', 'category' => 'Retail Collection', 'reference' => fake()->unique()->uuid(), 'amount' => 100, 'description' => fake()->sentence(),
        ];
    }
}
