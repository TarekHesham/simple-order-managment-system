<?php

namespace App\Services;

use App\DTOs\ReceiveData;
use App\Models\Product\Receive;
use Illuminate\Pagination\LengthAwarePaginator;

class ReceiveService
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Receive::with(['product', 'supplier', 'user'])->paginate($perPage);
    }

    public function find(int $id): ?Receive
    {
        return Receive::with(['product', 'supplier', 'user'])->find($id);
    }

    public function create(ReceiveData $data): Receive
    {
        return Receive::create([
            'product_id'   => $data->product_id,
            'user_id'      => $data->user_id,
            'supplier_id'  => $data->supplier_id,
            'quantity'     => $data->quantity,
            'unit_price'   => $data->unit_price,
            'total_amount' => $data->total_amount,
        ]);
    }

    public function update(Receive $receive, ReceiveData $data): Receive
    {
        $receive->update([
            'product_id'   => $data->product_id,
            'user_id'      => $data->user_id,
            'supplier_id'  => $data->supplier_id,
            'quantity'     => $data->quantity,
            'unit_price'   => $data->unit_price,
            'total_amount' => $data->total_amount,
        ]);
        return $receive;
    }

    public function delete(Receive $receive): bool
    {
        return $receive->delete();
    }
}
