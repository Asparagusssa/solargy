<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductProperty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductProperty>
 */
class ProductPropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement(['property', 'recommendation']),
            'html' => fake()->text('300'),
            'file' => null,
            'file_name' => null,
            'image' => 'test/1.jpg',
        ];
    }

    public function forProduct(Product $product): ProductPropertyFactory
    {
        return $this->state([
            'product_id' => $product->id,
        ]);
    }
}
