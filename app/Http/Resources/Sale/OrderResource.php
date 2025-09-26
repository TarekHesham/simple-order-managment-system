<?php

namespace App\Http\Resources\Sale;

use App\Http\Resources\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        $items = OrderItemResource::collection($this->whenLoaded('items'));

        return [
            'id'              => $this->id,
            'order_number'    => $this->order_number,
            'customer'        => new CustomerResource($this->whenLoaded('customer')),
            'items'           => $items,
            'total_amount'    => $this->total_amount,
            'refunded_amount' => $this->refunded_amount,
            'status'          => $this->status,
            'sale'            => $this->whenLoaded('sale') ? new SaleResource($this->sale) : null,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
