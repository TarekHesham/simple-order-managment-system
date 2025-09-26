<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('supplier')?->id;

        return [
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:suppliers,email,' . $id,
            'phone_number' => 'required|string|max:20',
        ];
    }
}
