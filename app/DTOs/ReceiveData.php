<?php

namespace App\DTOs;

use Illuminate\Support\Facades\Auth;

class ReceiveData
{
    public function __construct(
        public int $product_id,
        public int $user_id,
        public int $supplier_id,
        public int $quantity,
        public float $unit_price,
        public float $total_amount,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            product_id: $data['product_id'],
            user_id: Auth::id(),
            supplier_id: $data['supplier_id'],
            quantity: $data['quantity'],
            unit_price: $data['unit_price'],
            total_amount: $data['total_amount'],
        );
    }
}
