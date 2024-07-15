<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAddressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "street" => "min:1|max:255",
            "number" => "min:1|max:255",
            "neighborhood" => "min:1|max:255",
            "additional" => "max:255",
            "city" => "min:1|max:255",
            "state" => "min:1|max:2",
            "country" => "min:1|max:2",
            "postal_code" => "min:1|max:50"
        ];
    }

    public function messages(): array
    {
        return [
            'max' => "O campo :attribute excede a quantidade máxima de :max caracteres.",
            "min" => "O campo :attribute não pode estar vazio."
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
