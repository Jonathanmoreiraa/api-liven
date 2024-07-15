<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
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
                'errors' => "Erro ao criar o endereço!"
            ], 422);
        }

        return response()->json([
            'message' => 'Endereço criado com sucesso!',
            'address' => $address
        ], 201);
    }

    public function getAdresseses(Request $request){
        $addressesQuery = Address::query();
        $user = Auth::user();

        $addressesQuery->where("user_id", '=', $user->id);

        if ($request->filled('street')) {
            $addressesQuery->where('street', 'like', '%'.$request->input('street').'%');
        }

        if ($request->filled('number')) {
            $addressesQuery->where('number', 'like', '%'.$request->input('number').'%');
        }

        if ($request->filled('neighborhood')) {
            $addressesQuery->where('neighborhood', 'like', '%'.$request->input('neighborhood').'%');
        }

        if ($request->filled('additional')) {
            $addressesQuery->where('additional', 'like', '%'.$request->input('additional').'%');
        }

        if ($request->filled('city')) {
            $addressesQuery->where('city', 'like', '%'.$request->input('city').'%');
        }

        if ($request->filled('state')) {
            $addressesQuery->where('state', 'like', '%'.$request->input('state').'%');
        }

        if ($request->filled('country')) {
            $addressesQuery->where('country', 'like', '%'.$request->input('country').'%');
        }

        if ($request->filled('postal_code')) {
            $formattedPostalCode = $request->input('postal_code');

            if (strlen($request->postal_code) == 8) {
                $formattedPostalCode = substr($request->postal_code, 0, 5) . '-' . substr($request->postal_code, 5);
            }

            $addressesQuery->where('postal_code', 'like', '%'.$formattedPostalCode.'%');
        }

        $addresses = $addressesQuery->get();

        if (!$addresses) {
            return response()->json([
                'errors' => "Erro ao retornar os endereços!"
            ], 422);
        }else if ($addresses && count($addresses) == 0) {
            return response()->json([
                'errors' => "Nenhum endereço encontrado!"
            ], 422);
        }
        return response()->json([
            'data' => $addresses
        ], 200);
    }

    public function getAdressById(string $id){
        $user = Auth::user();

        $address = Address::where([
            "user_id" => $user->id
        ])->find($id);

        if (!$address) {
            return response()->json([
                'errors' => "Nenhum endereço encontrado com este id!"
            ], 422);
        }

        return response()->json([
            'data' => $address
        ], 200);
    }

    public function update(UpdateAddressRequest $request, string $id)
    {
        $user = Auth::user();

        $address = Address::where([
            'id' => $id,
            "user_id" => $user->id
        ])->find($id);

        if (!$address) {
            return response()->json([
                'errors' => "Erro ao encontrar endereço com esse id!",
            ], 422);
        }

        try {
            $requestUpdate = $request->only([
                "street", 
                "number", 
                "neighborhood", 
                "additional", 
                "city",
                "state",
                "country",
                "postal_code"
            ]);

            Address::where([
                'id' => $id,
                "user_id" => $user->id
            ])->update($requestUpdate);

            $address = Address::find($id);
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => "Erro ao editar o endereço!",
            ], 422);
        }
        
        return response()->json([
            'message' => 'Endereço editado com sucesso!',
            'address' => $address
        ], 200);
    }

    public function delete(string $id){
        $user = Auth::user();
        $addressFind = Address::where(["user_id" => $user->id])->find($id);

        if (!$addressFind) {
            return response()->json([
                'errors' => "Nenhum endereço encontrado com esse id!",
            ], 422);
        }

        try {
            $address = Address::destroy($id);
            
            return response()->json([
                'data' => "Endereço deletado com sucesso!"
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => "Erro ao deletar o endereço!",
            ], 422);
        }
    }
}