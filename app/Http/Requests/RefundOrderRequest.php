<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefundOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'The amount field is required.',
            'amount.numeric'  => 'The amount field must be a number.',
            'amount.min'      => 'The amount field must be at least 0.',
            'reason.string'   => 'The reason field must be a string.',
            'reason.max'      => 'The reason field must not exceed 255 characters.',
        ];
    }
}
