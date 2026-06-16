<?php

namespace Database\Factories;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Faculty>
 */
class FacultyFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'Faculty of '.fake()->unique()->word();

        return [
            'name_en' => $name,
            'name_ar' => 'كلية '.fake()->word(),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 99999),
            'description_en' => fake()->sentence(),
            'description_ar' => 'وصف الكلية',
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => ['is_active' => false]);
    }
}
