<?php

namespace App\Http\Controllers;

use App\Http\Requests\Availabilities\AvailableSlotsRequest;
use App\Http\Requests\Availabilities\StoreAvailabilityRequest;
use App\Http\Requests\Availabilities\UpdateAvailabilityRequest;
use App\Models\Availability;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AvailabilityController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Availability::query()
                ->orderBy('id')
                ->get()
                ->map(static fn (Availability $availability): array => [
                    'id' => $availability->id,
                    'user_id' => $availability->user_id,
                    'day_of_week' => $availability->day_of_week,
                    'start_time' => $availability->start_time,
                    'end_time' => $availability->end_time,
                    'active' => (bool) $availability->active,
                ])
                ->values()
        );
    }

    public function store(StoreAvailabilityRequest $request): JsonResponse
    {
        $availability = Availability::query()->create($request->validated());

        return response()->json([
            'id' => $availability->id,
            'user_id' => $availability->user_id,
            'day_of_week' => $availability->day_of_week,
            'start_time' => $availability->start_time,
            'end_time' => $availability->end_time,
            'active' => (bool) $availability->active,
        ], 201);
    }

    public function update(UpdateAvailabilityRequest $request, Availability $availability): JsonResponse
    {
        $availability->update($request->validated());

        return response()->json([
            'id' => $availability->id,
            'user_id' => $availability->user_id,
            'day_of_week' => $availability->day_of_week,
            'start_time' => $availability->start_time,
            'end_time' => $availability->end_time,
            'active' => (bool) $availability->active,
        ]);
    }

    public function destroy(Availability $availability): Response
    {
        $availability->delete();

        return response()->noContent();
    }

    public function availableSlots(AvailableSlotsRequest $request): JsonResponse
    {
        $dayOfWeek = Carbon::parse($request->string('date')->toString())->dayOfWeekIso;

        $slots = Availability::query()
            ->where('user_id', $request->integer('attendant_id'))
            ->where('active', true)
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time')
            ->get()
            ->map(static fn (Availability $availability): array => [
                'start_time' => substr($availability->start_time, 0, 5),
                'end_time' => substr($availability->end_time, 0, 5),
            ])
            ->values();

        return response()->json($slots);
    }
}
