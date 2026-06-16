<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_can_submit_a_public_lead(): void
    {
        $program = Program::factory()->create();

        $response = $this->postJson('/api/leads', [
            'program_id' => $program->id,
            'full_name' => 'Mohammed Al-Otaibi',
            'email' => 'mo@example.com',
            'phone' => '+966500000000',
            'nationality' => 'Saudi Arabia',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['message', 'data' => ['application_number', 'status']]);

        $this->assertDatabaseHas('applications', [
            'email' => 'mo@example.com',
            'program_id' => $program->id,
            'user_id' => null,
            'source' => 'website',
        ]);

        $this->assertNotNull(Application::first()->application_number);
    }

    public function test_public_lead_is_validated(): void
    {
        $this->postJson('/api/leads', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['program_id', 'full_name', 'email']);
    }
}
