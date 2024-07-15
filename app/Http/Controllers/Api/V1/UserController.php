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
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function __construct() 
    {
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
                'message' => 'Erro ao realizar o login!',
            ], 422);
        }

        $user = Auth::guard('api')->user();

        return response()->json([
            'user' => $user,
            'Authorization' => [
                'token' => $token,
                'type' => 'Bearer',
                'expires_in' => config('jwt.ttl') * 180
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

        $updatedUser = User::findOrFail($id);
        
        return response()->json([
            'message' => 'Usuário editado com sucesso!',
            'user' => $updatedUser
        ], 200);
    }

    public function getUserInfo()
    {
        $user = Auth::user();
        $user->addresses = $user->addresses;

        if (!$user) {
            return response()->json([
                'errors' => "Erro ao encontrar o usuário!",
            ], 422);
        }

        return response()->json([
            'data' => $user
        ], 200);
    }

    public function delete(string $id){
        try {
            $userFind = User::findOrFail($id);
            
            $user = User::destroy($id);
            
            return response()->json([
                'data' => "Usuário deletado com sucesso!"
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => "Erro ao deletar o usuário!",
            ], 422);
        }
    }

    public function refresh()
    {
        $token = JWTAuth::parseToken()->refresh();
    
        if ($token) {
            return response()->json([
                'user' => JWTAuth::setToken($token)->toUser(),
                'Authorization' => [
                    'token' => $token,
                    'type' => 'Bearer',
                    'expires_in' => config('jwt.ttl') * 180
                ]
            ]);
        } else {
            return response()->json([
                'message' => 'Erro ao atualizar o token!',
            ], 409);
        }
    }

    public function logout(){
        Auth::guard('api')->logout();
        return response()->json([
            'message' => 'Usuário deslogado com sucesso!',
        ], 200);
    }

}
