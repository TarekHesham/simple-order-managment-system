<?php

namespace App\Services;

use App\DTOs\ProductData;
use App\Models\Product\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Product::paginate($perPage);
    }

    public function store(ProductData $dto): Product
    {
        return Product::create([
            'name'        => $dto->name,
            'description' => $dto->description,
            'price'       => $dto->price,
            'stock'       => $dto->stock,
            'is_active'   => $dto->is_active,
        ]);
    }

    public function update(Product $product, ProductData $dto): Product
    {
        $product->update([
            'name'        => $dto->name,
            'description' => $dto->description,
            'price'       => $dto->price,
            'is_active'   => $dto->is_active,
        ]);

        return $product;
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }
}
