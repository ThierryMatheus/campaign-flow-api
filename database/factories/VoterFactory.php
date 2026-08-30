<?php

namespace Database\Factories;

use App\Models\Voter;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Voter>
 */
class VoterFactory extends Factory
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
            'name' => fake()->name(),
            'cpf' => fake()->numerify('###.###.###-##'),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'birth_date' => fake()->date(),
            'gender' => fake()->randomElement(['M', 'F']),
            'street' => fake()->streetName(),
            'number' => fake()->buildingNumber(),
            'neighborhood' => fake()->citySuffix(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'zipcode' => fake()->postcode(),
            'status' => 'unknown',
            'origin' => 'door_to_door',
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
