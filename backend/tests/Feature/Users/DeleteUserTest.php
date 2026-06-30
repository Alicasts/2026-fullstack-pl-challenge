<?php

namespace Tests\Feature\Users;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_an_attendant(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $attendant = User::factory()->create([
            'role' => UserRole::ATTENDANT->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->deleteJson("/api/users/{$attendant->id}");

        $response->assertNoContent();
        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', [
            'id' => $attendant->id,
        ]);
    }

    public function test_admin_can_delete_another_admin_when_another_admin_still_exists(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $otherAdmin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->deleteJson("/api/users/{$otherAdmin->id}");

        $response->assertNoContent();
        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', [
            'id' => $otherAdmin->id,
        ]);
    }

    public function test_last_admin_cannot_be_deleted(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->deleteJson("/api/users/{$admin->id}");

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The last administrator cannot be deleted.',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }

    public function test_attendant_receives_403_when_deleting_user(): void
    {
        $attendant = User::factory()->create([
            'role' => UserRole::ATTENDANT->value,
        ]);

        $otherUser = User::factory()->create([
            'role' => UserRole::ATTENDANT->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$attendant->createToken('attendant-token')->plainTextToken)
            ->deleteJson("/api/users/{$otherUser->id}");

        $response->assertForbidden();
    }

    public function test_unauthenticated_user_receives_401_when_deleting_user(): void
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertUnauthorized();
    }
}
