<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate([
            'email' => 'admin@app.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN->value,
        ]);
    }
}
