<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|decimal|min:0',
            'stock'       => 'required|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required'        => 'The name field is required.',
            'name.string'          => 'The name field must be a string.',
            'name.max'             => 'The name field must not exceed 255 characters.',
            'description.string'   => 'The description field must be a string.',
            'price.required'       => 'The price field is required.',
            'price.decimal'        => 'The price field must be a decimal.',
            'price.min'            => 'The price field must be greater than or equal to 0.',
            'stock.required'       => 'The stock field is required.',
            'stock.integer'        => 'The stock field must be an integer.',
            'stock.min'            => 'The stock field must be greater than or equal to 0.',
            'is_active.boolean'    => 'The is active field must be a boolean.',
        ];
    }
}
