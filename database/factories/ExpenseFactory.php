<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'expense_id' => fake()->unique()->bothify('EXP-#####'), 'date' => '2026-09-01', 'category' => 'Fuel', 'source' => 'Cash in Hand', 'amount' => 500, 'status' => 'Pending Verification', 'created_by' => 'Admin',
        ];
    }
}
