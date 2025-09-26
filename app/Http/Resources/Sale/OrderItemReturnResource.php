<?php

namespace App\Http\Resources\Sale;

use App\Http\Resources\Auth\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemReturnResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'order_item_id' => $this->order_item_id,
            'processed_by'  => $this->processed_by,
            'processor'     => new UserResource($this->whenLoaded('processedBy')),
            'quantity'      => $this->quantity,
            'amount'        => $this->amount,
            'reason'        => $this->reason,
            'created_at'    => $this->created_at,
        ];
    }
}
