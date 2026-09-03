<?php

namespace Database\Factories;

use App\Models\Demand;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Demand>
 */
class DemandFactory extends Factory
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
            'voter_id' => null,
            'created_by' => User::factory(),
            'assigned_to' => null,
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'status' => 'open',
            'priority' => 'medium',
            'category' => fake()->optional()->randomElement(['health', 'education', 'infrastructure', 'social', 'other']),
            'resolved_at' => null,
        ];
    }
}
