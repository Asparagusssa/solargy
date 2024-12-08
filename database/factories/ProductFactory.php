<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text('20'),
            'description' => fake()->text('100'),
            'price' => fake()->randomFloat('2', 0, 100000),
        ];
    }

    public function forCategory(Category $category): ProductFactory
    {
        return $this->state([
            'category_id' => $category->id,
        ]);
    }
}
