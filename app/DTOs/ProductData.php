<?php

namespace App\DTOs;

class ProductData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly float $price,
        public readonly int $stock,
        public readonly bool $is_active,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            price: $data['price'],
            stock: $data['stock'],
            is_active: $data['is_active'],
        );
    }
}
