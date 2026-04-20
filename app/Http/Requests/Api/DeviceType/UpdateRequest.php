<?php

namespace App\Http\Requests\Api\DeviceType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Разрешаем выполнение запроса.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации.
     */
    public function rules(): array
    {
        // Получаем ID текущего типа из маршрута, чтобы исключить его из проверки unique
        $deviceTypeId = $this->route('device_type') ? $this->route('device_type')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                // Исключаем текущий ID, чтобы можно было сохранить то же имя при обновлении других полей
                Rule::unique('device_types', 'name')->ignore($deviceTypeId),
            ],

            'icon'        => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Сообщения об ошибках.
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'Тип устройства с таким названием уже существует в базе.',
        ];
    }
}
