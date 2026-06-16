<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register_and_receives_a_token(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Sara Ali',
            'email' => 'sara@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'nationality' => 'Kuwait',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in', 'user' => ['id', 'email', 'role']])
            ->assertJsonPath('user.role', UserRole::Student->value);

        $this->assertDatabaseHas('users', ['email' => 'sara@example.com', 'role' => UserRole::Student->value]);
    }

    public function test_registration_requires_valid_data(): void
    {
        $this->postJson('/api/auth/register', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_a_user_can_login_with_valid_credentials(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        $this->postJson('/api/auth/login', [
            'email' => 'john@example.com',
            'password' => 'password',
        ])->assertOk()->assertJsonStructure(['access_token', 'user']);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        $this->postJson('/api/auth/login', [
            'email' => 'john@example.com',
            'password' => 'wrong-password',
        ])->assertStatus(401);
    }

    public function test_me_endpoint_requires_authentication(): void
    {
        $this->getJson('/api/auth/me')->assertStatus(401);
    }

    public function test_authenticated_user_can_fetch_their_profile(): void
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/auth/me')
            ->assertOk()
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_deactivated_user_cannot_login(): void
    {
        User::factory()->create(['email' => 'inactive@example.com', 'is_active' => false]);

        $this->postJson('/api/auth/login', [
            'email' => 'inactive@example.com',
            'password' => 'password',
        ])->assertStatus(403);
    }
}
