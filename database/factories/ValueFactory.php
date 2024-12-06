<?php

namespace Database\Factories;

use App\Models\Option;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Value>
 */
class ValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'option_id' => Option::query()->inRandomOrder()->first()->id,
            'value' => fake()->word,
            'price' => fake()->numberBetween(0, 15000),
            'image' => rand(0, 1) ? fake()->imageUrl : null,
        ];
    }
}
