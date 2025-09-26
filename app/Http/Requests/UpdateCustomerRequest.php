<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('customer');

        return [
            'name'         => 'sometimes|string|max:255',
            'email'        => 'sometimes|email|unique:customers,email,' . $customerId,
            'phone_number' => 'sometimes|string|max:20|unique:customers,phone_number,' . $customerId,
            'address'      => 'sometimes|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'name.string'         => 'The name must be a string.',
            'name.max'            => 'The name must not be greater than 255 characters.',
            'email.email'         => 'The email must be a valid email address.',
            'email.unique'        => 'The email has already been taken.',
            'phone_number.string' => 'The phone number must be a string.',
            'phone_number.max'    => 'The phone number must not be greater than 20 characters.',
            'phone_number.unique' => 'The phone number has already been taken.',
            'address.string'      => 'The address must be a string.',
            'address.max'         => 'The address must not be greater than 500 characters.',
        ];
    }
}
