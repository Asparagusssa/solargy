<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PageSection>
 */
class PageSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text('20'),
            'html' => fake()->text('300'),
            'test/1.jpg'
        ];
    }

    public function forPage(Page $page): PageSectionFactory
    {
        return $this->state([
            'page_id' => $page->id,
        ]);

    }
}
