<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promo>
 */
class PromoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text('20'),
            'description' => fake()->text,
            'image' => 'test/1.jpg',
            'start' => fake()->dateTimeBetween('-1 year', 'now','Europe/Samara'),
            'end' => fake()->dateTimeBetween('now+1 day', '+1 year','Europe/Samara'),
        ];
    }
}
