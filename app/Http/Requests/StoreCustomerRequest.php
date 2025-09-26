<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:customers,email',
            'phone_number' => 'required|digits:15|unique:customers,phone_number',
            'address'      => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'The name field is required.',
            'name.string'           => 'The name field must be a string.',
            'name.max'              => 'The name field must not exceed 255 characters.',
            'email.required'        => 'The email field is required.',
            'email.email'           => 'The email field must be a valid email address.',
            'email.unique'          => 'The email field must be unique.',
            'phone_number.required' => 'The phone number field is required.',
            'phone_number.string'   => 'The phone number field must be a digits.',
            'phone_number.max'      => 'The phone number field must not exceed 15 digits.',
            'address.required'      => 'The address field is required.',
            'address.string'        => 'The address field must be a string.',
            'address.max'           => 'The address field must not exceed 500 characters.',
        ];
    }
}
