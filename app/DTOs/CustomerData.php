<?php

namespace App\DTOs;

class CustomerData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $phone_number,
        public readonly string $address,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['email'],
            $data['phone_number'],
            $data['address'],
        );
    }
}
