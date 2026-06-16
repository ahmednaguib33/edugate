<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->student(),
            'program_id' => Program::factory(),
            'status' => ApplicationStatus::Pending,
            'full_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->e164PhoneNumber(),
            'nationality' => fake()->country(),
            'current_education' => 'High School',
            'preferred_intake' => 'Fall '.fake()->numberBetween(2026, 2028),
            'notes' => fake()->sentence(),
        ];
    }
}
