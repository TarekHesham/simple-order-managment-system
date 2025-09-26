<?php

namespace App\DTOs;

class OrderItemData
{
    public int $product_id;
    public int $quantity;
    public ?string $unit_price;
    public ?string $total_price;

    public function __construct(array $data)
    {
        $this->product_id  = (int) $data['product_id'];
        $this->quantity    = (int) $data['quantity'];
        $this->unit_price  = isset($data['unit_price'])  ? (string) $data['unit_price']  : null;
        $this->total_price = isset($data['total_price']) ? (string) $data['total_price'] : null;
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}
