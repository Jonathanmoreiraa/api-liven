<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => "max:255",
            "email" => "email|max:255|unique:users"
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'max' => "O campo :attribute excede a quantidade máxima de 255 caracteres.",
            'unique' => "O campo :attribute deve ser único.",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
