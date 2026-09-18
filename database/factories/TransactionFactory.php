<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workspace_id' => Workspace::factory(),
            'created_by' => User::factory(),
            'type' => fake()->randomElement(['donation', 'expense']),
            'category' => fake()->randomElement(['cash', 'transfer', 'material', 'fuel', 'advertising', 'event', 'other']),
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->sentence(),
            'amount' => fake()->randomFloat(2, 50, 5000),
            'occurred_at' => fake()->dateTimeBetween('-60 days', 'now'),
            'payment_method' => fake()->optional()->randomElement(['cash', 'pix', 'transfer', 'card', 'other']),
            'donor_or_vendor' => fake()->optional()->name(),
        ];
    }
}
