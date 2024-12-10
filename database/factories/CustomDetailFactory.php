<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CustomDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomDetail>
 */
class CustomDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text('10'),
            'value' => fake()->text('25'),
        ];
    }

    public function forCompany(Company $company): CustomDetailFactory
    {
        return $this->state([
            'company_id' => $company->id,
        ]);
    }
}
