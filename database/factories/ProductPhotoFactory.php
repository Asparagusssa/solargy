<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductPhotoFactory extends Factory
{
    protected $model = ProductPhoto::class;

    public function definition(): array
    {
        return [
            'photo' => 'test/1.jpg'
        ];
    }

    public function forProduct(Product $product): ProductPhotoFactory
    {
        return $this->state([
            'product_id' => $product->id,
        ]);
    }
}
