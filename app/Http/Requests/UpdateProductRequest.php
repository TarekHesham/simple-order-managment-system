<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'sometimes|numeric|min:0',
            'is_active'   => 'sometimes|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.string'          => 'The name field must be a string.',
            'name.max'             => 'The name field must not exceed 255 characters.',
            'description.string'   => 'The description field must be a string.',
            'price.decimal'        => 'The price field must be a decimal.',
            'price.min'            => 'The price field must be greater than or equal to 0.',
            'is_active.boolean'    => 'The is active field must be a boolean.',
        ];
    }
}
