<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'role'    => $this->role,
            'email'   => $this->email,

            // Load technical details only if the 'details' relationship is requested via ?include=details
            'network' => new DetailResource($this->whenLoaded('details')),
        ];
    }
}
