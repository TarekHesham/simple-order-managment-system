<?php

namespace App\DTOs;

class SupplierData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $phone_number,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            phone_number: $data['phone_number'],
        );
    }
}
