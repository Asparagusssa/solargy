<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MainBanner;
use App\Models\Option;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\Promo;
use App\Models\SubBanner;
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
        User::create([
            'name' => 'Admin',
            'email' => 'Admin@admin.com',
            'password' => 'secret-password'
        ]);

        $category = Category::factory(4)->create();

        $category->each(function ($category) {
            Product::factory()->count(4)->forCategory($category)->create();
        });

        $products = Product::all();
        $products->each(function ($product) {
            ProductPhoto::factory()->count(3)->forProduct($product)->create();
        });

        Option::factory(5)->create();
        Value::factory(20)->create();

        MainBanner::factory(5)->create();
        SubBanner::factory(5)->create();

        Promo::factory(5)->create();

    }
}
