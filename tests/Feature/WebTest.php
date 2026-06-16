<?php

namespace Tests\Feature;

use App\Models\Faculty;
use App\Models\Program;
use App\Models\University;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders(): void
    {
        University::factory()->create();
        Program::factory()->create();

        $this->get('/')
            ->assertOk()
            ->assertSee('EduGate', false);
    }

    public function test_programs_page_lists_programs(): void
    {
        $program = Program::factory()->create(['title_en' => 'Bachelor of Robotics']);

        $this->withSession(['locale' => 'en'])
            ->get('/programs')
            ->assertOk()
            ->assertSee('Bachelor of Robotics');
    }

    public function test_program_detail_page_renders(): void
    {
        $faculty = Faculty::factory()->create();
        $program = Program::factory()->for($faculty)->create(['slug' => 'bachelor-medicine']);
        $program->universities()->attach(University::factory()->create()->id);

        $this->withSession(['locale' => 'en'])
            ->get('/programs/bachelor-medicine')
            ->assertOk()
            ->assertSee($program->title_en);
    }

    public function test_inactive_program_detail_returns_404(): void
    {
        Program::factory()->create(['slug' => 'hidden', 'is_active' => false]);

        $this->get('/programs/hidden')->assertNotFound();
    }

    public function test_website_lead_form_creates_an_application(): void
    {
        $program = Program::factory()->create();

        $response = $this->post('/apply', [
            'program_id' => $program->id,
            'full_name' => 'Khaled Saleh',
            'email' => 'khaled@example.com',
            'phone' => '+966512345678',
            'nationality' => 'Saudi Arabia',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('lead_success');

        $this->assertDatabaseHas('applications', [
            'email' => 'khaled@example.com',
            'program_id' => $program->id,
            'user_id' => null,
            'source' => 'website',
        ]);
    }

    public function test_language_can_be_switched_to_english(): void
    {
        $this->get('/lang/en')->assertRedirect();

        $this->get('/')->assertSee('Browse programs');
    }
}
