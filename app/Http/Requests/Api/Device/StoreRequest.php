<?php

namespace App\Http\Requests\Api\Device;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Разрешаем запрос (позже здесь можно будет вызвать $this->user()->can('create', Device::class))
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации
     */
    public function rules(): array
    {
        return [
            // Связи: проверяем, что такая модель и локация реально существуют в БД
            'device_model_id'  => 'required|integer|exists:device_models,id',
            'location_id'      => 'required|integer|exists:locations,id',

            // Идентификаторы: должны быть уникальными во всей таблице devices
            'serial_number'    => 'nullable|string|max:255|unique:devices,serial_number',
            'inventory_number' => 'nullable|string|max:255|unique:devices,inventory_number',

            // Статус: ограничиваем выбор конкретным списком
            'status'           => ['required', 'string', Rule::in(['stock', 'active', 'repair', 'retired'])],

            // Сетевые параметры: используем встроенную проверку Laravel для IP
            'ip_address'       => 'nullable|ip',

            // MAC-адрес: можно проверить через регулярку (стандартный формат 00:00:00:00:00:00)
            'mac_address'      => [
                'nullable',
                'string',
                'regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/'
            ],

            // Гибкие данные и даты
            'specs'            => 'nullable|array',
            'purchase_date'    => 'nullable|date',
            'warranty_expire'  => 'nullable|date|after_or_equal:purchase_date', // Гарантия не может кончиться раньше покупки

            'description'      => 'nullable|string|max:1000',
        ];
    }

    /**
     * Кастомные сообщения об ошибках (опционально, для удобства фронтенда)
     */
    public function messages(): array
    {
        return [
            'mac_address.regex' => 'Формат MAC-адреса должен быть типа 00:1A:2B:3C:4D:5E',
            'warranty_expire.after_or_equal' => 'Дата окончания гарантии не может быть раньше даты покупки.',
        ];
    }
}
