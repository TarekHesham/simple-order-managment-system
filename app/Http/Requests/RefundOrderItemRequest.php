<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefundOrderItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => 'required|integer|min:1',
            'amount'   => 'nullable|decimal|min:0',
            'reason'   => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'The quantity field is required.',
            'quantity.integer'  => 'The quantity field must be an integer.',
            'quantity.min'      => 'The quantity field must be at least 1.',
            'amount.decimal'    => 'The amount field must be a number.',
            'amount.min'        => 'The amount field must be greater than or equal to 0.01.',
            'reason.string'     => 'The reason field must be a string.',
            'reason.max'        => 'The reason field must not exceed 255 characters.',
        ];
    }
}
