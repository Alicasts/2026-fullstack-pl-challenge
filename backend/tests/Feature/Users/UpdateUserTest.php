<?php

namespace Tests\Feature\Users;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_another_user(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $user = User::factory()->create([
            'name' => 'Maria Silva',
            'email' => 'maria@example.com',
            'role' => UserRole::ATTENDANT->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->putJson("/api/users/{$user->id}", [
                'name' => 'Maria Souza',
                'role' => UserRole::ADMIN->value,
            ]);

        $response->assertOk();
        $response->assertExactJson([
            'id' => $user->id,
            'name' => 'Maria Souza',
            'email' => 'maria@example.com',
            'role' => UserRole::ADMIN->value,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Maria Souza',
            'email' => 'maria@example.com',
            'role' => UserRole::ADMIN->value,
        ]);
    }

    public function test_admin_can_update_self(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'role' => UserRole::ADMIN->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->putJson("/api/users/{$admin->id}", [
                'name' => 'Admin Updated',
                'role' => UserRole::ATTENDANT->value,
            ]);

        $response->assertOk();
        $response->assertJsonPath('id', $admin->id);
        $response->assertJsonPath('name', 'Admin Updated');
        $response->assertJsonPath('email', $admin->email);
        $response->assertJsonPath('role', UserRole::ATTENDANT->value);
    }

    public function test_attendant_can_update_self(): void
    {
        $attendant = User::factory()->create([
            'name' => 'Maria Silva',
            'role' => UserRole::ATTENDANT->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$attendant->createToken('attendant-token')->plainTextToken)
            ->putJson("/api/users/{$attendant->id}", [
                'name' => 'Maria Souza',
                'role' => UserRole::ATTENDANT->value,
            ]);

        $response->assertOk();
        $response->assertJsonPath('id', $attendant->id);
        $response->assertJsonPath('name', 'Maria Souza');
        $response->assertJsonPath('email', $attendant->email);
        $response->assertJsonPath('role', UserRole::ATTENDANT->value);
    }

    public function test_attendant_cannot_update_another_user(): void
    {
        $attendant = User::factory()->create([
            'role' => UserRole::ATTENDANT->value,
        ]);

        $otherUser = User::factory()->create([
            'role' => UserRole::ATTENDANT->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$attendant->createToken('attendant-token')->plainTextToken)
            ->putJson("/api/users/{$otherUser->id}", [
                'name' => 'Blocked Update',
                'role' => UserRole::ADMIN->value,
            ]);

        $response->assertForbidden();
    }

    public function test_unauthenticated_user_receives_401(): void
    {
        $user = User::factory()->create();

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'Maria Souza',
            'role' => UserRole::ATTENDANT->value,
        ]);

        $response->assertUnauthorized();
    }

    public function test_role_must_be_valid(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $user = User::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->putJson("/api/users/{$user->id}", [
                'name' => 'Maria Souza',
                'role' => 'INVALID',
            ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['role']);
    }

    public function test_name_is_required(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $user = User::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->putJson("/api/users/{$user->id}", [
                'role' => UserRole::ATTENDANT->value,
            ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_email_change_is_ignored(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $user = User::factory()->create([
            'email' => 'original@example.com',
            'role' => UserRole::ATTENDANT->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->putJson("/api/users/{$user->id}", [
                'name' => 'Maria Souza',
                'role' => UserRole::ADMIN->value,
                'email' => 'changed@example.com',
            ]);

        $response->assertOk();
        $response->assertJsonPath('email', 'original@example.com');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'original@example.com',
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => 'changed@example.com',
        ]);
    }

    public function test_password_change_is_ignored(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $user = User::factory()->create([
            'password' => 'password123',
            'role' => UserRole::ATTENDANT->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->putJson("/api/users/{$user->id}", [
                'name' => 'Maria Souza',
                'role' => UserRole::ATTENDANT->value,
                'password' => 'changed-password',
                'password_confirmation' => 'changed-password',
            ]);

        $response->assertOk();

        $storedUser = User::query()->findOrFail($user->id);

        $this->assertTrue(Hash::check('password123', $storedUser->password));
        $this->assertFalse(Hash::check('changed-password', $storedUser->password));
    }

    public function test_changes_are_persisted_in_postgresql(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $user = User::factory()->create([
            'name' => 'Maria Silva',
            'role' => UserRole::ATTENDANT->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->putJson("/api/users/{$user->id}", [
                'name' => 'Maria Souza',
                'role' => UserRole::ADMIN->value,
            ]);

        $response->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Maria Souza',
            'role' => UserRole::ADMIN->value,
        ]);
    }
}
