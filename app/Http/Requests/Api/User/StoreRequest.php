<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Currently allows all requests (admin permission check will be added later)
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Custom body parameters for Scribe documentation.
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'The user\'s full name for display within the inventory system.',
                'example' => 'John Doe',
            ],
            'email' => [
                'description' => 'Work email address. Must be unique in the users table.',
                'example' => 'admin@inventory.test',
            ],
            'password' => [
                'description' => 'Login password. Minimum 8 characters; should include numbers.',
                'example' => 'StrongPassword123!',
            ],
        ];
    }
}
