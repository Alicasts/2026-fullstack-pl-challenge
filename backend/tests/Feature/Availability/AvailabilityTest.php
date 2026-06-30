<?php

namespace Tests\Feature\Availability;

use App\Enums\UserRole;
use App\Models\Availability;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_availabilities(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $attendant = User::factory()->create([
            'role' => UserRole::ATTENDANT->value,
        ]);

        Availability::query()->create([
            'user_id' => $attendant->id,
            'day_of_week' => 1,
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'active' => true,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->getJson('/api/availabilities');

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonPath('0.user_id', $attendant->id);
    }

    public function test_admin_can_create_availability(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $attendant = User::factory()->create([
            'role' => UserRole::ATTENDANT->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->postJson('/api/availabilities', [
                'user_id' => $attendant->id,
                'day_of_week' => 2,
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'active' => true,
            ]);

        $response->assertCreated();
        $this->assertDatabaseHas('availabilities', [
            'user_id' => $attendant->id,
            'day_of_week' => 2,
            'active' => true,
        ]);
    }

    public function test_admin_can_update_availability(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $attendant = User::factory()->create([
            'role' => UserRole::ATTENDANT->value,
        ]);

        $availability = Availability::query()->create([
            'user_id' => $attendant->id,
            'day_of_week' => 1,
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'active' => true,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->putJson("/api/availabilities/{$availability->id}", [
                'user_id' => $attendant->id,
                'day_of_week' => 3,
                'start_time' => '13:00:00',
                'end_time' => '15:00:00',
                'active' => false,
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('availabilities', [
            'id' => $availability->id,
            'day_of_week' => 3,
            'start_time' => '13:00:00',
            'end_time' => '15:00:00',
            'active' => false,
        ]);
    }

    public function test_admin_can_delete_availability(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $attendant = User::factory()->create([
            'role' => UserRole::ATTENDANT->value,
        ]);

        $availability = Availability::query()->create([
            'user_id' => $attendant->id,
            'day_of_week' => 1,
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'active' => true,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->deleteJson("/api/availabilities/{$availability->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('availabilities', [
            'id' => $availability->id,
        ]);
    }

    public function test_attendant_receives_403_when_accessing_availabilities(): void
    {
        $attendant = User::factory()->create([
            'role' => UserRole::ATTENDANT->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$attendant->createToken('attendant-token')->plainTextToken)
            ->getJson('/api/availabilities');

        $response->assertForbidden();
    }

    public function test_unauthenticated_user_receives_401_when_accessing_availabilities(): void
    {
        $response = $this->getJson('/api/availabilities');

        $response->assertUnauthorized();
    }

    public function test_end_time_must_be_greater_than_start_time(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $attendant = User::factory()->create([
            'role' => UserRole::ATTENDANT->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->postJson('/api/availabilities', [
                'user_id' => $attendant->id,
                'day_of_week' => 2,
                'start_time' => '10:00:00',
                'end_time' => '09:00:00',
                'active' => true,
            ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['end_time']);
    }

    public function test_user_id_must_reference_an_attendant(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $adminUser = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->postJson('/api/availabilities', [
                'user_id' => $adminUser->id,
                'day_of_week' => 2,
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'active' => true,
            ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['user_id']);
    }

    public function test_day_of_week_must_be_between_0_and_6(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $attendant = User::factory()->create([
            'role' => UserRole::ATTENDANT->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->postJson('/api/availabilities', [
                'user_id' => $attendant->id,
                'day_of_week' => 7,
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'active' => true,
            ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['day_of_week']);
    }
}
