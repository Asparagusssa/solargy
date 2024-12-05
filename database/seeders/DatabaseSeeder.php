<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Value;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $products = Product::factory(15)->create();
        $products->each(function ($product) {
            ProductPhoto::factory()->count(3)->forProduct($product)->create();
        });

        Option::factory(5)->create();


        Value::factory(20)->create();

    }
}
