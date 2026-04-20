<?php

namespace App\Http\Resources\Api\DeviceType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\DeviceModel\IndexResource as DeviceTypeResource;

class IndexResource extends JsonResource
{
    /**
     * Трансформируем ресурс в массив.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'icon'        => $this->icon ?? 'pi-box', // Дефолтная иконка, если не задана
            'description' => $this->description,

            // Считаем количество привязанных моделей (если вызвано withCount в контроллере)
            'models_count' => $this->whenCounted('models'),

            // Если нужно вывести сами модели (иерархия)
            'models' => DeviceTypeResource::collection($this->whenLoaded('models')),

            'created_at'  => $this->created_at->format('d.m.Y H:i'),
        ];
    }
}
