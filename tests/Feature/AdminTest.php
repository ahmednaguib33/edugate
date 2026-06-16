<?php

namespace Tests\Feature;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\University;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function headersFor(User $user): array
    {
        $token = auth('api')->login($user);

        return ['Authorization' => "Bearer $token"];
    }

    public function test_a_student_cannot_access_the_admin_area(): void
    {
        $headers = $this->headersFor(User::factory()->student()->create());

        $this->withHeaders($headers)->getJson('/api/admin/dashboard/stats')
            ->assertStatus(403);
    }

    public function test_an_admin_can_view_dashboard_stats(): void
    {
        $headers = $this->headersFor(User::factory()->admin()->create());
        Program::factory()->create();
        Application::factory()->create();

        $this->withHeaders($headers)->getJson('/api/admin/dashboard/stats')
            ->assertOk()
            ->assertJsonStructure([
                'data' => ['universities', 'faculties', 'programs', 'students', 'agents', 'applications'],
            ]);
    }

    public function test_an_agent_can_update_an_application_status(): void
    {
        $agent = User::factory()->agent()->create();
        $headers = $this->headersFor($agent);
        $application = Application::factory()->create();

        $this->withHeaders($headers)->patchJson("/api/admin/applications/{$application->id}", [
            'status' => ApplicationStatus::Accepted->value,
            'assigned_agent_id' => $agent->id,
            'admin_notes' => 'Looks good.',
        ])->assertOk()->assertJsonPath('data.status.value', ApplicationStatus::Accepted->value);

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => ApplicationStatus::Accepted->value,
            'assigned_agent_id' => $agent->id,
        ]);
    }

    public function test_an_admin_can_create_a_program_with_universities(): void
    {
        $headers = $this->headersFor(User::factory()->admin()->create());
        $faculty = Faculty::factory()->create();
        $universities = University::factory()->count(2)->create()->pluck('id')->all();

        $response = $this->withHeaders($headers)->postJson('/api/admin/programs', [
            'faculty_id' => $faculty->id,
            'degree_level' => 'bachelor',
            'title_en' => 'Bachelor of Medicine',
            'title_ar' => 'بكالوريوس الطب',
            'language' => 'both',
            'tuition_min' => 6000,
            'tuition_max' => 8000,
            'min_admission_rate' => 70,
            'university_ids' => $universities,
        ]);

        $response->assertCreated()->assertJsonCount(2, 'data.universities');

        $this->assertDatabaseHas('programs', ['title_en' => 'Bachelor of Medicine', 'slug' => 'bachelor-of-medicine']);
    }

    public function test_an_agent_cannot_create_a_program(): void
    {
        $headers = $this->headersFor(User::factory()->agent()->create());

        $this->withHeaders($headers)->postJson('/api/admin/programs', [])
            ->assertStatus(403);
    }
}
