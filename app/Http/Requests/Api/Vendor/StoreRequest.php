<?php

namespace App\Http\Requests\Api\Vendor;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        return [
            'name' => 'required|string|unique:vendors,name,' . $this->route('vendor')?->id,
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url',
            'is_active' => 'boolean'
        ];
    }
}
