<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'             => $this->id,
            'name'           => $this->name,
            'email'          => $this->email,
            'phone_number'   => $this->phone_number,
            'is_super_admin' => $this->hasRole('super-admin'),
            'is_staff'       => $this->hasRole('staff'),
            'permissions'    => $this->getAllPermissions()?->pluck('name'),
        ];

        if ($this->token) {
            $data['token'] = $this->token;
        }

        return $data;
    }
}
