<?php

namespace Tests\Feature;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsStudent(): array
    {
        $student = User::factory()->student()->create();
        $token = auth('api')->login($student);

        return [$student, ['Authorization' => "Bearer $token"]];
    }

    public function test_a_student_can_submit_an_application(): void
    {
        [$student, $headers] = $this->actingAsStudent();
        $program = Program::factory()->create();

        $response = $this->withHeaders($headers)->postJson('/api/applications', [
            'program_id' => $program->id,
            'full_name' => 'Sara Ali',
            'email' => 'sara@example.com',
            'phone' => '+96550000000',
            'nationality' => 'Kuwait',
            'current_education' => 'High School',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.status.value', ApplicationStatus::Pending->value)
            ->assertJsonPath('data.program.id', $program->id);

        $this->assertDatabaseHas('applications', [
            'user_id' => $student->id,
            'program_id' => $program->id,
        ]);

        $this->assertNotNull(Application::first()->application_number);
    }

    public function test_application_submission_is_validated(): void
    {
        [, $headers] = $this->actingAsStudent();

        $this->withHeaders($headers)->postJson('/api/applications', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['program_id', 'full_name', 'email']);
    }

    public function test_a_student_only_sees_their_own_applications(): void
    {
        [$student, $headers] = $this->actingAsStudent();
        Application::factory()->for($student, 'student')->create();
        Application::factory()->create(); // belongs to another student

        $this->withHeaders($headers)->getJson('/api/applications')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_a_student_cannot_view_another_students_application(): void
    {
        [, $headers] = $this->actingAsStudent();
        $other = Application::factory()->create();

        $this->withHeaders($headers)->getJson("/api/applications/{$other->id}")
            ->assertStatus(403);
    }

    public function test_applying_requires_authentication(): void
    {
        $program = Program::factory()->create();

        $this->postJson('/api/applications', ['program_id' => $program->id])
            ->assertStatus(401);
    }
}
