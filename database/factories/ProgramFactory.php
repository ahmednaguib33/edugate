<?php

namespace Database\Factories;

use App\Enums\DegreeLevel;
use App\Enums\ProgramLanguage;
use App\Models\Faculty;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = 'Bachelor of '.fake()->unique()->word();
        $min = fake()->numberBetween(3000, 6000);

        return [
            'faculty_id' => Faculty::factory(),
            'degree_level' => DegreeLevel::Bachelor,
            'title_en' => $title,
            'title_ar' => 'برنامج '.fake()->word(),
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 99999),
            'description_en' => fake()->paragraph(),
            'description_ar' => 'وصف البرنامج',
            'tuition_min' => $min,
            'tuition_max' => $min + fake()->numberBetween(1000, 3000),
            'currency' => 'USD',
            'min_admission_rate' => fake()->numberBetween(50, 90),
            'duration_years' => fake()->randomElement([4, 5, 6]),
            'language' => ProgramLanguage::Both,
            'highlights' => ['Accredited certificates', 'Practical training'],
            'is_featured' => false,
            'is_active' => true,
        ];
    }

    public function master(): static
    {
        return $this->state(fn (array $attributes) => ['degree_level' => DegreeLevel::Master]);
    }

    public function phd(): static
    {
        return $this->state(fn (array $attributes) => ['degree_level' => DegreeLevel::Phd]);
    }
}
