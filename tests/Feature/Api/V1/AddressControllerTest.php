<?php

namespace Tests\Feature\Api\V1;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\CreatesApplication;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    use CreatesApplication, DatabaseTransactions;

    public function test_add_address_successfully()
    {
        $user = User::create([
            'name' => 'Name example',
            'email' => 'example@example.com',
            'password' => Hash::make('password'),
        ]);
        
        $token = $this->postJson('/api/v1/user/login', [
            "email" => $user->email,
            "password" => "password"
        ]);

        $data = [
            "street"=> "Rua das Flores",
            "number"=> "123",
            "neighborhood"=> "Jardim Primavera",
            "additional"=> "Apt 45",
            "city"=> "São Carlos",
            "state"=> "SP",
            "country"=> "BR",
            "postal_code"=> "13560-123"
        ];

        $response = $this->withHeader("Authorization", 'Bearer ' . $token["Authorization"]["token"])
        ->json('POST', "/api/v1/user/address", $data)
        ->assertCreated()
        ->assertJson([
            'message' => 'Endereço criado com sucesso!',
            'address' => [
                "street"=> "Rua das Flores",
                "number"=> "123",
                "neighborhood"=> "Jardim Primavera",
                "additional"=> "Apt 45",
                "city"=> "São Carlos",
                "state"=> "SP",
                "country"=> "BR",
                "postal_code"=> "13560-123"
            ]
        ]);
    }

    public function test_get_address_with_query_string_successfully()
    {
        $user = User::create([
            'name' => 'Name example',
            'email' => 'example@example.com',
            'password' => Hash::make('password'),
        ]);
        
        $token = $this->postJson('/api/v1/user/login', [
            "email" => $user->email,
            "password" => "password"
        ]);

        Address::create([
            "user_id" => $user->id,
            "street"=> "Rua das Flores",
            "number"=> "123",
            "neighborhood"=> "Jardim Primavera",
            "additional"=> "Apt 45",
            "city"=> "São Carlos",
            "state"=> "SP",
            "country"=> "BR",
            "postal_code"=> "13560-123"
        ]);

        $response = $this->withHeader("Authorization", 'Bearer ' . $token["Authorization"]["token"])
        ->json('GET', "/api/v1/user/address?country=BR")
        ->assertOk()
        ->assertJsonStructure([
            'data'
        ]);
    }

    public function test_get_address_with_id_successfully()
    {
        $user = User::create([
            'name' => 'Name example',
            'email' => 'example@example.com',
            'password' => Hash::make('password'),
        ]);
        
        $token = $this->postJson('/api/v1/user/login', [
            "email" => $user->email,
            "password" => "password"
        ]);

        $address = Address::create([
            "user_id" => $user->id,
            "street"=> "Rua das Flores",
            "number"=> "123",
            "neighborhood"=> "Jardim Primavera",
            "additional"=> "Apt 45",
            "city"=> "São Carlos",
            "state"=> "SP",
            "country"=> "BR",
            "postal_code"=> "13560-123"
        ]);

        $response = $this->withHeader("Authorization", 'Bearer ' . $token["Authorization"]["token"])
        ->json('GET', "/api/v1/user/address/$address->id")
        ->assertOk()
        ->assertJsonStructure([
            'data'
        ]);
    }

    public function test_add_address_validation_error()
    {
        $user = User::create([
            'name' => 'Name example',
            'email' => 'example@example.com',
            'password' => Hash::make('password'),
        ]);
        
        $token = $this->postJson('/api/v1/user/login', [
            "email" => $user->email,
            "password" => "password"
        ]);

        $data = [
            "street"=> "",
            "number"=> "",
            "neighborhood"=> "",
            "additional"=> "Apt 45",
            "city"=> "",
            "state"=> "",
            "country"=> "",
            "postal_code"=> ""
        ];

        $response = $this->withHeader("Authorization", 'Bearer ' . $token["Authorization"]["token"])
        ->json('POST', "/api/v1/user/address", $data)
        ->assertUnprocessable()
        ->assertJsonStructure([
            "errors"
        ]);
    }
}