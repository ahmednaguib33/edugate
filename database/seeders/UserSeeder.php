<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@edugate.test'],
            [
                'name' => 'EduGate Admin',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
                'phone' => '+201000000001',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'agent@edugate.test'],
            [
                'name' => 'Sales Agent',
                'password' => Hash::make('password'),
                'role' => UserRole::Agent,
                'phone' => '+201000000002',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@edugate.test'],
            [
                'name' => 'Test Student',
                'password' => Hash::make('password'),
                'role' => UserRole::Student,
                'phone' => '+966500000000',
                'nationality' => 'Saudi Arabia',
                'country_of_residence' => 'Saudi Arabia',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
