<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Detail>
 */
class DetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake('RU_ru')->company(),
            'office' => fake()->address(),
            'production' => fake()->address(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
        ];
    }

    public function forCompany(Company $company): DetailFactory
    {
        return $this->state([
            'company_id' => $company->id,
        ]);
    }
}
