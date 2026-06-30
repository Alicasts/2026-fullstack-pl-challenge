<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            User::query()
                ->select(['id', 'name', 'email', 'role'])
                ->orderBy('id')
                ->get()
                ->map(static fn (User $user): array => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role->value,
                ])
                ->values()
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::query()->create([
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'password' => Hash::make($request->string('password')->toString()),
            'role' => UserRole::from($request->string('role')->toString())->value,
        ]);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role->value,
        ], 201);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $currentUser = $request->user();

        if (! $currentUser || (! $currentUser->is($user) && $currentUser->role !== UserRole::ADMIN)) {
            abort(403);
        }

        $user->update([
            'name' => $request->string('name')->toString(),
            'role' => UserRole::from($request->string('role')->toString())->value,
        ]);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role->value,
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->role === UserRole::ADMIN && User::query()->where('role', UserRole::ADMIN->value)->count() <= 1) {
            return response()->json([
                'message' => 'The last administrator cannot be deleted.',
            ], 422);
        }

        $user->delete();

        return response()->noContent();
    }
}
