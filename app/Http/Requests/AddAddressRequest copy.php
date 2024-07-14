<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddAddressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "street" => "required|min:1|max:255",
            "number" => "required|min:1|max:255",
            "neighborhood" => "required|min:1|max:255",
            "additional" => "nullable|max:255",
            "city" => "required|min:1|max:255",
            "state" => "required|min:1|max:2",
            "country" => "required|min:1|max:2",
            "postal_code" => "required|min:1|max:50"
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'max' => "O campo :attribute excede a quantidade máxima de :max caracteres.",
            "min" => "O campo :attribute não pode estar vazio."

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
