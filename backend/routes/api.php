<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/users', [UserController::class, 'index']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::middleware('role:ADMIN')->delete('/users/{user}', [UserController::class, 'destroy']);

    Route::get('/me', function (Request $request) {
        return response()->json([
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'role' => $request->user()->role->value,
            ],
        ]);
    });

    Route::middleware('role:ADMIN')->get('/admin/ping', function () {
        return response()->json([
            'message' => 'ok',
        ]);
    });

    Route::middleware('role:ADMIN')->post('/users', [UserController::class, 'store']);
});
