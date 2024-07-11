<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => "required|max:255",
            "email" => "required|email|max:255|unique:users",
            "password" => "required|min:8"
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'max' => "O campo :attribute excede a quantidade máxima de 255 caracteres.",
            'unique' => "O campo :attribute deve ser único.",
            "password.min" => "A senha deve conter no mínimo 8 caracteres."
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
