<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReceiveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id'   => 'sometimes|exists:products,id',
            'supplier_id'  => 'sometimes|exists:suppliers,id',
            'quantity'     => 'sometimes|integer|min:1',
            'unit_price'   => 'sometimes|numeric|min:0',
            'total_amount' => 'sometimes|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.exists'    => 'Product not found.',
            'supplier_id.exists'   => 'Supplier not found.',
            'quantity.integer'     => 'Quantity must be an integer.',
            'quantity.min'         => 'Quantity must be at least 1.',
            'unit_price.numeric'   => 'Unit price must be a number.',
            'unit_price.min'       => 'Unit price must be at least 0.',
            'total_amount.numeric' => 'Total amount must be a number.',
            'total_amount.min'     => 'Total amount must be at least 0.',
        ];
    }
}
