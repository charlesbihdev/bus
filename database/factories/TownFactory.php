<?php

namespace Database\Factories;

use App\Models\Town;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Town>
 */
class TownFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->city(),
            'region' => fake()->state(),
            'is_active' => true,
        ];
    }
}
