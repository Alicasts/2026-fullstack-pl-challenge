<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_succeeds_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'password',
            'role' => UserRole::ADMIN->value,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'token',
            'token_type',
            'user' => [
                'id',
                'name',
                'email',
                'role',
            ],
        ]);
        $response->assertJsonPath('token_type', 'Bearer');
        $response->assertJsonPath('user.id', $user->id);
        $response->assertJsonPath('user.name', $user->name);
        $response->assertJsonPath('user.email', $user->email);
        $response->assertJsonPath('user.role', UserRole::ADMIN->value);

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_login_fails_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'alice@example.com',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'alice@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertUnauthorized();
        $response->assertExactJson([
            'message' => 'Invalid credentials.',
        ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_login_fails_with_nonexistent_email(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'missing@example.com',
            'password' => 'password',
        ]);

        $response->assertUnauthorized();
        $response->assertExactJson([
            'message' => 'Invalid credentials.',
        ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_login_validates_required_fields(): void
    {
        $response = $this->postJson('/api/login', []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'email',
            'password',
        ]);
    }
}
