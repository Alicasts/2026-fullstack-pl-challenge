<?php

namespace App\Http\Requests\Availabilities;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id', function ($attribute, $value, $fail): void {
                $user = \App\Models\User::query()->find($value);

                if ($user && $user->role !== UserRole::ATTENDANT) {
                    $fail('The selected user must be an attendant.');
                }
            }],
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s', 'after:start_time'],
            'active' => ['required', 'boolean'],
        ];
    }
}
