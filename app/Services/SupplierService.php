<?php

namespace App\Services;

use App\DTOs\SupplierData;
use App\Models\Product\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SupplierService
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Supplier::paginate($perPage);
    }

    public function all(): Collection
    {
        return Supplier::all();
    }

    public function find(int $id): ?Supplier
    {
        return Supplier::find($id);
    }

    public function create(SupplierData $data): Supplier
    {
        return Supplier::create([
            'name'         => $data->name,
            'email'        => $data->email,
            'phone_number' => $data->phone_number,
        ]);
    }

    public function update(Supplier $supplier, SupplierData $data): Supplier
    {
        $supplier->update([
            'name'         => $data->name,
            'email'        => $data->email,
            'phone_number' => $data->phone_number,
        ]);
        return $supplier;
    }

    public function delete(Supplier $supplier): bool
    {
        return $supplier->delete();
    }
}
