<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if(!$user){
            return response()->json([
                'errors' => "Erro ao criar o usuário!",
            ], 422);
        }

        return response()->json([
            'message' => 'Usuário criado com sucesso!',
            'user' => $user
        ], 201);
    }
}
