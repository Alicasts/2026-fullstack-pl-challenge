<?php

namespace App\Http\Requests\Availabilities;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class AvailableSlotsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attendant_id' => ['required', 'exists:users,id', function ($attribute, $value, $fail): void {
                $user = \App\Models\User::query()->find($value);

                if ($user && $user->role !== UserRole::ATTENDANT) {
                    $fail('The selected attendant is invalid.');
                }
            }],
            'date' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
