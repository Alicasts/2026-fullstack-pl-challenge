<?php

namespace Tests\Feature\Availability;

use App\Enums\UserRole;
use App\Models\Availability;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailableSlotsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_active_availability_windows_for_the_requested_day(): void
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
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'active' => true,
        ]);

        Availability::query()->create([
            'user_id' => $attendant->id,
            'day_of_week' => 2,
            'start_time' => '13:00:00',
            'end_time' => '17:00:00',
            'active' => true,
        ]);

        Availability::query()->create([
            'user_id' => $attendant->id,
            'day_of_week' => 1,
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
            'active' => false,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->getJson('/api/available-slots?attendant_id='.$attendant->id.'&date=2026-07-06');

        $response->assertOk();
        $response->assertExactJson([
            [
                'start_time' => '08:00',
                'end_time' => '12:00',
            ],
        ]);
    }

    public function test_it_requires_a_valid_attendant_user(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN->value,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$admin->createToken('admin-token')->plainTextToken)
            ->getJson('/api/available-slots?attendant_id=999999&date=2026-07-06');

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['attendant_id']);
    }

    public function test_it_requires_authentication(): void
    {
        $response = $this->getJson('/api/available-slots?attendant_id=1&date=2026-07-06');

        $response->assertUnauthorized();
    }
}
