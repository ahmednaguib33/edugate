<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Program;
use App\Models\University;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('email', 'student@edugate.test')->first();
        $agent = User::where('email', 'agent@edugate.test')->first();

        if ($student === null) {
            return;
        }

        $cairo = University::where('slug', 'cairo-university')->first();
        $ainShams = University::where('slug', 'ain-shams-university')->first();

        $rows = [
            [
                'slug' => 'bachelor-medicine',
                'status' => ApplicationStatus::Reviewing,
                'university' => $cairo,
                'agent' => $agent,
                'intake' => 'Fall 2026',
            ],
            [
                'slug' => 'bachelor-engineering',
                'status' => ApplicationStatus::Pending,
                'university' => null,
                'agent' => null,
                'intake' => 'Spring 2027',
            ],
            [
                'slug' => 'master-computers-information',
                'status' => ApplicationStatus::Accepted,
                'university' => $ainShams,
                'agent' => $agent,
                'intake' => 'Fall 2026',
            ],
        ];

        foreach ($rows as $row) {
            $program = Program::where('slug', $row['slug'])->first();

            if ($program === null) {
                continue;
            }

            Application::firstOrCreate(
                [
                    'user_id' => $student->id,
                    'program_id' => $program->id,
                ],
                [
                    'preferred_university_id' => $row['university']?->id,
                    'assigned_agent_id' => $row['agent']?->id,
                    'status' => $row['status'],
                    'full_name' => $student->name,
                    'email' => $student->email,
                    'phone' => $student->phone,
                    'nationality' => $student->nationality,
                    'current_education' => 'High School Diploma',
                    'preferred_intake' => $row['intake'],
                    'notes' => 'Interested in studying at an accredited Egyptian university.',
                ]
            );
        }
    }
}
