<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductPhotoFactory extends Factory
{
    protected $model = ProductPhoto::class;

    public function definition(): array
    {
        return [
            'test/1.jpg'
        ];
    }

    public function forProduct(Product $product): ProductPhotoFactory
    {
        return $this->state([
            'product_id' => $product->id,
        ]);
    }
}
