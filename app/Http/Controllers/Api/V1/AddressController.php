<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddAddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth:api');
    }

    public function add(AddAddressRequest $request)
    {
        $user = Auth::user();

        if(!$user){
            return response()->json([
                'errors' => "Erro ao verificar o usuário autenticado!",
            ], 422);
        }

        try {
            $formattedPostalCode = $request->postal_code;

            if (strlen($request->postal_code) == 8) {
                $formattedPostalCode = substr($request->postal_code, 0, 5) . '-' . substr($request->postal_code, 5);
            }

            $address = Address::create([
                "user_id" => $user->id,
                "street" => $request->street,
                "number" => $request->number,
                "neighborhood" => $request->neighborhood,
                "additional" => $request->additional ? $request->additional : null,
                "city" => $request->city,
                "state" => $request->state,
                "country" => $request->country,
                "postal_code" => $formattedPostalCode
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'errors' => "Erro ao criar o endereço!",
                "message" => $th
            ], 422);
        }

        return response()->json([
            'message' => 'Endereço criado com sucesso!',
            'address' => $address
        ], 201);
    }
}
