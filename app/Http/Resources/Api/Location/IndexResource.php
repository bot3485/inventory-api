<?php

namespace App\Http\Resources\Api\Location;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // app/Http/Resources/Api/Location/IndexResource.php

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'prefix' => $this->prefix,
            'type' => $this->type,
            'address' => $this->address,
            'metadata' => $this->metadata, // Наши фото, видео, карты
            'sort_order' => $this->sort_order,
            'description' => $this->description,
            'is_active' => $this->is_active,
            // Рекурсивная подгрузка детей (дерево)
            'children' => IndexResource::collection($this->whenLoaded('children')),
            'parent' => new IndexResource($this->whenLoaded('parent')),
        ];
    }
}
