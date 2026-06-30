<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_access_without_token_returns_401(): void
    {
        $response = $this->getJson('/api/me');

        $response->assertUnauthorized();
    }

    public function test_access_with_valid_token_is_allowed(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::ATTENDANT->value,
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/me');

        $response->assertOk();
        $response->assertJsonPath('user.id', $user->id);
        $response->assertJsonPath('user.email', $user->email);
        $response->assertJsonPath('user.role', UserRole::ATTENDANT->value);
    }

    public function test_admin_is_authorized_for_admin_route(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/ping');

        $response->assertOk();
        $response->assertExactJson([
            'message' => 'ok',
        ]);
    }

    public function test_attendant_is_blocked_for_admin_route(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::ATTENDANT->value,
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/ping');

        $response->assertForbidden();
    }
}
