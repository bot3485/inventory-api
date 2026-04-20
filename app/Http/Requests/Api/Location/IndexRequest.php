<?php

namespace App\Http\Requests\Api\Location;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $locationId = $this->route('location') ? $this->route('location')->id : null;

        return [
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id',
            'prefix'    => 'nullable|string|max:10|unique:locations,prefix,' . $locationId,
            'type'      => 'required|string|in:building,floor,room,rack,shelf',
            'address'   => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'metadata'  => 'nullable|array', // Валидируем как массив (Laravel сам переведет в JSONB)
        ];
    }
}
