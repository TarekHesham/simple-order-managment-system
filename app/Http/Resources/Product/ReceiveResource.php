<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiveResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'product'      => $this->product,
            'supplier'     => $this->supplier,
            'user'         => $this->user,
            'quantity'     => $this->quantity,
            'unit_price'   => $this->unit_price,
            'total_amount' => $this->total_amount,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
