<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailResource extends JsonResource
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
            'ip'          => $this->ip_address,
            'user_agent'  => $this->user_agent,
            'active_at'   => $this->last_active_at?->diffForHumans(), // Returns "3 minutes ago"
        ];
    }
}
