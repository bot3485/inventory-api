<?php

namespace App\Http\Requests\Api\DeviceType;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Разрешаем выполнение запроса.
     */
    public function authorize(): bool
    {
        // В будущем здесь можно проверить: return $this->user()->isAdmin();
        return true;
    }

    /**
     * Правила валидации.
     */
    public function rules(): array
    {
        return [
            // Название обязательно и должно быть уникальным в таблице device_types
            'name'        => 'required|string|max:100|unique:device_types,name',

            // Иконка (например, из библиотеки Lucide или FontAwesome)
            'icon'        => 'nullable|string|max:50',

            // Описание категории
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Сообщения об ошибках.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Название типа устройства обязательно.',
            'name.unique'   => 'Такой тип устройства уже существует.',
        ];
    }
}
