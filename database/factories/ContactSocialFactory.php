<?php

namespace Database\Factories;

use App\Models\ContactSocial;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ContactSocialFactory extends Factory
{
    protected $model = ContactSocial::class;

    public function definition(): array
    {
        return [
            'platform' => fake()->word,
            'url' => fake()->url,
            'image' => 'test/1.jpg'
        ];
    }
}
