<?php

namespace App\Http\Resources\Api\Device;

use App\Http\Resources\Api\Location\IndexResource as LocationResource;
use App\Http\Resources\Api\DeviceModel\IndexResource as DeviceModelResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    /**
     * Трансформируем ресурс в массив.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'serial_number'    => $this->serial_number,
            'inventory_number' => $this->inventory_number,
            'status'           => $this->status,
            'ip_address'       => $this->ip_address,
            'mac_address'      => $this->mac_address,

            // Наш любимый гибкий JSONB
            'specs'            => $this->specs,

            // Даты в удобном формате (Y-m-d)
            'purchase_date'    => $this->purchase_date?->format('Y-m-d'),
            'warranty_expire'  => $this->warranty_expire?->format('Y-m-d'),

            'description'      => $this->description,

            // --- СВЯЗИ (Eager Loading) ---
            // Мы используем whenLoaded, чтобы не делать лишних запросов к БД,
            // если в контроллере мы не вызвали ->with('model')
            'model'    => new DeviceModelResource($this->whenLoaded('model')),
            'location' => new LocationResource($this->whenLoaded('location')),

            // Технические поля
            'created_at' => $this->created_at->format('d.m.Y H:i'),
            'updated_at' => $this->updated_at->format('d.m.Y H:i'),
        ];
    }
}
