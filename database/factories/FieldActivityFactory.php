<?php

namespace Database\Factories;

use App\Models\FieldActivity;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FieldActivity>
 */
class FieldActivityFactory extends Factory
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
        'team_id' => null,
        'user_id' => User::factory(),
        'type' => 'visit',
        'result' => 'neutral',
        'notes' => fake()->optional()->sentence(),
        'performed_at' => now(),
        'latitude' => fake()->optional()->latitude(),
        'longitude' => fake()->optional()->longitude(),        ];
    }
}
