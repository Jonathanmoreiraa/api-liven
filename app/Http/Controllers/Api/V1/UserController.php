<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

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
    
    public function login(LoginUserRequest $request){
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::guard('api')->user();

        return response()->json([
            'user' => $user,
            'Authorization' => [
                'token' => $token,
                'type' => 'Bearer',
                'expires_in' => config('jwt.ttl') * 60
            ]
        ],200);
    }

    public function update(UpdateUserRequest $request, string $id)
    {

        try {
            $requestUpdate = $request->only(["name", "email"]);
            $userUpdated = User::where([
                'id' => $id
            ])->update($requestUpdate);
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => "Erro ao editar o usuário!",
            ], 422);
        }

        $updatedUser = User::with(['addresses'])->findOrFail($id);
        
        return response()->json([
            'message' => 'Usuário editado com sucesso!',
            'user' => $updatedUser
        ], 200);
    }

}
