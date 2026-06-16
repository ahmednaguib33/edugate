<?php

namespace Tests\Feature;

use App\Models\Faculty;
use App\Models\Program;
use App\Models\University;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_only_active_programs(): void
    {
        $faculty = Faculty::factory()->create();
        Program::factory()->for($faculty)->create(['is_active' => true]);
        Program::factory()->for($faculty)->create(['is_active' => false]);

        $response = $this->getJson('/api/programs');

        $response->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_it_filters_programs_by_degree_level(): void
    {
        $faculty = Faculty::factory()->create();
        Program::factory()->for($faculty)->create();
        Program::factory()->for($faculty)->master()->create();

        $this->getJson('/api/programs?degree_level=master')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.degree_level.value', 'master');
    }

    public function test_it_shows_a_program_with_its_universities(): void
    {
        $faculty = Faculty::factory()->create();
        $program = Program::factory()->for($faculty)->create(['slug' => 'bachelor-medicine']);
        $program->universities()->attach(University::factory()->create()->id);

        $this->getJson('/api/programs/bachelor-medicine')
            ->assertOk()
            ->assertJsonPath('data.slug', 'bachelor-medicine')
            ->assertJsonCount(1, 'data.universities');
    }

    public function test_it_returns_404_for_an_inactive_program(): void
    {
        $program = Program::factory()->create(['slug' => 'hidden-program', 'is_active' => false]);

        $this->getJson("/api/programs/{$program->slug}")->assertStatus(404);
    }

    public function test_it_lists_universities_and_faculties(): void
    {
        University::factory()->count(3)->create();
        Faculty::factory()->count(2)->create();

        $this->getJson('/api/universities')->assertOk()->assertJsonCount(3, 'data');
        $this->getJson('/api/faculties')->assertOk()->assertJsonCount(2, 'data');
    }
}
