<?php

namespace App\Services;

use App\DTOs\CustomerData;
use App\Models\Customer\Customer;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerService
{
    public function paginate(int $perPage = 10, ?string $query = null): LengthAwarePaginator
    {
        return Customer::when(
            $query,
            fn($q) => $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
        )->paginate($perPage);
    }

    public function find(int $id): ?Customer
    {
        return Customer::find($id);
    }

    public function create(CustomerData $data): Customer
    {
        return Customer::create([
            'name'         => $data->name,
            'email'        => $data->email,
            'phone_number' => $data->phone_number,
            'address'      => $data->address,
        ]);
    }

    public function update(int $id, CustomerData $data): ?Customer
    {
        $customer = Customer::find($id);
        if (! $customer) {
            return null;
        }
        $customer->update([
            'name'         => $data->name,
            'email'        => $data->email,
            'phone_number' => $data->phone_number,
            'address'      => $data->address,
        ]);
        return $customer;
    }

    public function delete(int $id): bool
    {
        $customer = Customer::find($id);
        if (! $customer) {
            return false;
        }
        return (bool) $customer->delete();
    }
}
