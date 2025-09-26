<?php

namespace App\DTOs;

class OrderData
{
    public int $customer_id;
    /** @var OrderItemData[] */
    public array $items;

    public function __construct(array $data)
    {
        $this->customer_id = (int) $data['customer_id'];
        $this->items       = array_map(function ($it) {
            return OrderItemData::fromArray($it);
        }, $data['items'] ?? []);
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}
