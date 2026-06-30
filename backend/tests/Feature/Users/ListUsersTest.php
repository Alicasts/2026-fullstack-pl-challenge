<?php

namespace Tests\Feature\Users;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_attendant_receive_the_same_users_list(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => UserRole::ADMIN->value,
        ]);

        $attendant = User::factory()->create([
            'name' => 'Attendant User',
            'email' => 'attendant@example.com',
            'role' => UserRole::ATTENDANT->value,
        ]);

        $thirdUser = User::factory()->create([
            'name' => 'Third User',
            'email' => 'third@example.com',
            'role' => UserRole::ATTENDANT->value,
        ]);

        $adminToken = $admin->createToken('admin-token')->plainTextToken;
        $attendantToken = $attendant->createToken('attendant-token')->plainTextToken;

        $adminResponse = $this->withHeader('Authorization', 'Bearer '.$adminToken)
            ->getJson('/api/users');

        $attendantResponse = $this->withHeader('Authorization', 'Bearer '.$attendantToken)
            ->getJson('/api/users');

        $adminResponse->assertOk();
        $attendantResponse->assertOk();

        $this->assertSame($adminResponse->json(), $attendantResponse->json());
        $this->assertCount(3, $adminResponse->json());

        $adminResponse->assertJsonPath('0.id', $admin->id);
        $adminResponse->assertJsonPath('0.name', $admin->name);
        $adminResponse->assertJsonPath('0.email', $admin->email);
        $adminResponse->assertJsonPath('0.role', UserRole::ADMIN->value);
        $adminResponse->assertJsonPath('1.id', $attendant->id);
        $adminResponse->assertJsonPath('2.id', $thirdUser->id);
    }
}
