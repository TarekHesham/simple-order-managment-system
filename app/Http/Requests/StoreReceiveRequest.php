<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReceiveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id'   => 'required|exists:products,id',
            'supplier_id'  => 'required|exists:suppliers,id',
            'quantity'     => 'required|integer|min:1',
            'unit_price'   => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required'   => 'The product field is required.',
            'supplier_id.required'  => 'The supplier field is required.',
            'quantity.required'     => 'The quantity field is required.',
            'quantity.integer'      => 'The quantity field must be an integer.',
            'quantity.min'          => 'The quantity field must be at least 1.',
            'unit_price.required'   => 'The unit price field is required.',
            'unit_price.numeric'    => 'The unit price field must be a number.',
            'unit_price.min'        => 'The unit price field must be at least 0.',
            'total_amount.required' => 'The total amount field is required.',
            'total_amount.numeric'  => 'The total amount field must be a number.',
            'total_amount.min'      => 'The total amount field must be at least 0.',
        ];
    }
}
