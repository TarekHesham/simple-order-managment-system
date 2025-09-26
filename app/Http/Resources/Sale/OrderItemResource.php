<?php

namespace App\Http\Resources\Sale;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'product'           => new ProductResource($this->whenLoaded('product')),
            'quantity'          => $this->quantity,
            'unit_price'        => $this->unit_price,
            'total_price'       => $this->total_price,
            'refunded_quantity' => $this->refunded_quantity,
            'refunded_amount'   => $this->refunded_amount,
            'returns'           => OrderItemReturnResource::collection($this->whenLoaded('returns')),
        ];
    }
}
