<?php

namespace Tests\Feature\Users;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->postJson('/api/users', [
                'name' => 'Maria Silva',
                'email' => 'maria@example.com',
                'role' => UserRole::ATTENDANT->value,
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertCreated();
        $response->assertExactJson([
            'id' => $response->json('id'),
            'name' => 'Maria Silva',
            'email' => 'maria@example.com',
            'role' => UserRole::ATTENDANT->value,
        ]);
        $response->assertJsonMissingPath('password');

        $this->assertDatabaseHas('users', [
            'email' => 'maria@example.com',
            'name' => 'Maria Silva',
            'role' => UserRole::ATTENDANT->value,
        ]);

        $storedUser = User::query()->where('email', 'maria@example.com')->firstOrFail();

        $this->assertTrue(Hash::check('password123', $storedUser->password));
    }

    public function test_attendant_receives_403_when_creating_user(): void
    {
        $attendant = User::factory()->create([
            'role' => UserRole::ATTENDANT->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$attendant->createToken('attendant-token')->plainTextToken)
            ->postJson('/api/users', [
                'name' => 'Maria Silva',
                'email' => 'maria@example.com',
                'role' => UserRole::ATTENDANT->value,
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertForbidden();
    }

    public function test_unauthenticated_user_receives_401_when_creating_user(): void
    {
        $response = $this->postJson('/api/users', [
            'name' => 'Maria Silva',
            'email' => 'maria@example.com',
            'role' => UserRole::ATTENDANT->value,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertUnauthorized();
    }

    public function test_duplicate_email_fails_validation(): void
    {
        User::factory()->create([
            'email' => 'maria@example.com',
        ]);

        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->postJson('/api/users', [
                'name' => 'Maria Silva',
                'email' => 'maria@example.com',
                'role' => UserRole::ATTENDANT->value,
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_password_must_have_minimum_length(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->postJson('/api/users', [
                'name' => 'Maria Silva',
                'email' => 'maria@example.com',
                'role' => UserRole::ATTENDANT->value,
                'password' => 'short',
                'password_confirmation' => 'short',
            ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_password_confirmation_must_match(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->postJson('/api/users', [
                'name' => 'Maria Silva',
                'email' => 'maria@example.com',
                'role' => UserRole::ATTENDANT->value,
                'password' => 'password123',
                'password_confirmation' => 'different123',
            ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['password_confirmation']);
    }

    public function test_role_must_be_valid(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->postJson('/api/users', [
                'name' => 'Maria Silva',
                'email' => 'maria@example.com',
                'role' => 'INVALID',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['role']);
    }
}
