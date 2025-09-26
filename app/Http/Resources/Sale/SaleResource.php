<?php

namespace App\Http\Resources\Sale;

use App\Http\Resources\Auth\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'user'       => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at,
        ];
    }
}
