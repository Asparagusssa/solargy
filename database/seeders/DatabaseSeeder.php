<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Contact;
use App\Models\ContactSocial;
use App\Models\CustomDetail;
use App\Models\Detail;
use App\Models\Email;
use App\Models\EmailType;
use App\Models\MainBanner;
use App\Models\Option;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\ProductProperty;
use App\Models\Promo;
use App\Models\PurchasePlace;
use App\Models\Seo;
use App\Models\SubBanner;
use App\Models\Team;
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
            ProductProperty::factory()->count(2)->forProduct($product)->create();
        });

        Option::factory(5)->create();
        Value::factory(20)->create();

        MainBanner::factory(5)->create();
        SubBanner::factory(5)->create();

        Promo::factory(5)->create();
        Team::factory(8)->create();
        Contact::factory(1)->create();
        ContactSocial::factory(2)->create();
        PurchasePlace::factory(10)->create();
        Email::factory(6)->create();
        Seo::factory(10)->create();

        $types = [
            [
                'type' => 'order'
            ],
            [
                'type' => 'contact'
            ],
            [
                'type' => 'support'
            ]
        ];

        foreach ($types as $type) {
            EmailType::create($type);
        }

        $companies = Company::factory(2)->create();

        $companies->each(function ($company) {
            Detail::factory(1)->forCompany($company)->create();
            CustomDetail::factory(10)->forCompany($company)->create();
        });

        $pages = [
            [
                'title' => 'promo',
                'url' => 'promo',
                'is_changeable' => true,
            ],
            [
                'title' => 'about',
                'url' => 'about',
                'is_changeable' => true,
            ],
            [
                'title' => 'delivery',
                'url' => 'delivery',
                'is_changeable' => true,
            ]
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }

        $pages = Page::all();
        $pages->each(function ($page) {
            PageSection::factory()->count(3)->forPage($page)->create();
        });
    }
}
