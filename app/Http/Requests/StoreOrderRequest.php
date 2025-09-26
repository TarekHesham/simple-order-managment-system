<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'        => 'required|integer|exists:customers,id',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required'        => 'The customer field is required.',
            'customer_id.integer'         => 'The customer field must be an integer.',
            'customer_id.exists'          => 'The customer field does not exist.',
            'items.required'              => 'The items field is required.',
            'items.array'                 => 'The items field must be an array.',
            'items.min'                   => 'The items field must have at least 1 item.',
            'items.*.product_id.required' => 'The product id field is required.',
            'items.*.product_id.integer'  => 'The product id field must be an integer.',
            'items.*.product_id.exists'   => 'The product id field does not exist.',
            'items.*.quantity.required'   => 'The quantity field is required.',
            'items.*.quantity.integer'    => 'The quantity field must be an integer.',
            'items.*.quantity.min'        => 'The quantity field must be at least 1.',
        ];
    }
}
