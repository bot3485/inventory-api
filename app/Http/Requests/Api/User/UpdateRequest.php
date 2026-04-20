<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled in the controller via UserPolicy
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Safely retrieve the user ID from the {user} route
        // If running via Scribe and the route is empty, $userId will be null
        $userId = $this->route('user')?->id;

        return [
            'name'  => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                // Safe: if $userId is null, ignore() will simply not ignore any record
                Rule::unique('users', 'email')->ignore($userId),
            ],
        ];
    }

    /**
     * Custom body parameters for Scribe documentation.
     */
    public function bodyParameters(): array
    {
        return [
            'name'  => [
                'description' => 'The updated name of the user.',
                'example' => 'John'
            ],
            'email' => [
                'description' => 'The new email address (must be unique).',
                'example' => 'john@example.com'
            ],
        ];
    }
}
