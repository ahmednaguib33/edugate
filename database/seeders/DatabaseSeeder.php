<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with the EduGate catalog and demo accounts.
     *
     * Note: model events are intentionally kept enabled so that the Application
     * model can auto-generate its application_number on creation.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            UniversitySeeder::class,
            FacultySeeder::class,
            ProgramSeeder::class,
            ApplicationSeeder::class,
        ]);
    }
}
