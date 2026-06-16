<?php

namespace Database\Factories;

use App\Models\University;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<University>
 */
class UniversityFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->city().' University';

        return [
            'name_en' => $name,
            'name_ar' => 'جامعة '.fake()->word(),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 99999),
            'city' => fake()->city(),
            'description_en' => fake()->sentence(),
            'description_ar' => 'وصف الجامعة',
            'is_accredited' => true,
            'established_year' => fake()->numberBetween(1900, 2010),
            'global_ranking' => fake()->numberBetween(500, 2000),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => ['is_active' => false]);
    }
}
