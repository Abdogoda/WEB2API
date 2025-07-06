<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user_id = Auth::id();

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user_id,
            'phone' => 'nullable|string|unique:users,phone,' . $user_id,
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
        ];
    }
}
