<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\CreatesApplication;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use CreatesApplication, DatabaseTransactions;

    public function test_register_user_successfully()
    {
        $data = [
            'name' => 'Usuário de teste',
            'email' => 'teste@teste.com',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/v1/user', $data);

        $response->assertStatus(201)
        ->assertJson([
            'message' => 'Usuário criado com sucesso!',
            'user' => [
                'name' => 'Usuário de teste',
                'email' => 'teste@teste.com',
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'teste@teste.com',
        ]);
    }

    public function test_login_user_successfully()
    {
        User::create([
            'name' => 'Usuário de teste',
            'email' => 'teste@teste.com',
            'password' => Hash::make('password'),
        ]);

        $data = [
            'email' => 'teste@teste.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/v1/user/login', $data);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'user',
            'Authorization' => [
                'token',
                'type',
                'expires_in',
            ],
        ]);
    }

    public function test_refresh_token_successfully()
    {
        $user = User::create([
            'name' => 'Usuário de teste',
            'email' => 'teste@teste.com',
            'password' => Hash::make('password'),
        ]);

        $token = $this->postJson('/api/v1/user/login', [
            "email" => $user->email,
            "password" => "password"
        ]);

        $response = $this->withHeader("Authorization", 'Bearer ' . $token["Authorization"]["token"])
        ->json('POST', "/api/v1/user/refresh")
        ->assertOk();

        $response->assertStatus(200)
        ->assertJsonStructure([
            'user',
            'Authorization' => [
                'token',
                'type',
                'expires_in',
            ],
        ]);
    }

    public function test_update_user_successfully()
    {
        $user = User::factory()->create();

        $token = $this->postJson('/api/v1/user/login', [
            "email" => $user->email,
            "password" => "password"
        ]);

        $updateData = [
            'name' => 'Name example',
            'email' => 'example@example.com'
        ];

        $response = $this->withHeader("Authorization", 'Bearer ' . $token["Authorization"]["token"])
        ->json('PUT', "/api/v1/user/{$user->id}", $updateData)
        ->assertOk();

        $response->assertStatus(200)
        ->assertJson([
            'message' => 'Usuário editado com sucesso!',
            'user' => [
                'id' => $user->id,
                'name' => 'Name example',
                'email' => 'example@example.com',
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Name example',
            'email' => 'example@example.com'
        ]);
    }

    public function test_get_user_data_successfully()
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

        $response = $this->withHeader("Authorization", 'Bearer ' . $token["Authorization"]["token"])
        ->json('GET', "/api/v1/user/me")
        ->assertOk();

        $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => 'Name example',
                'email' => 'example@example.com',
            ],
        ])
        ->assertJsonStructure([
            "data" => [
                "addresses"
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Name example',
            'email' => 'example@example.com'
        ]);
    } 
    
    public function test_logout_user_successfully()
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

        $response = $this->withHeader("Authorization", 'Bearer ' . $token["Authorization"]["token"])
        ->json('POST', "/api/v1/user/logout")
        ->assertOk();

        $response->assertStatus(200)
        ->assertJson([
            'message' => 'Usuário deslogado com sucesso!'
        ]);
    }

    public function test_delete_user_successfully()
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

        $response = $this->withHeader("Authorization", 'Bearer ' . $token["Authorization"]["token"])
        ->json('DELETE', "/api/v1/user/$user->id")
        ->assertOk();

        $response->assertStatus(200)
        ->assertJson([
            'data' => "Usuário deletado com sucesso!",
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }   

    public function test_login_user_with_invalid_credentials()
    {
        $user = User::create([
            'name' => 'Usuário de teste',
            'email' => 'teste@teste.com',
            'password' => Hash::make('password'),
        ]);

        $data = [
            'email' => 'teste@teste.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/v1/user/login', $data);

        $response->assertStatus(422)
        ->assertJson([
            'message' => 'Erro ao realizar o login!',
        ]);
    }

    public function test_update_user_validation_error()
    {
        $user = User::factory()->create();

        $token = $this->postJson('/api/v1/user/login', [
            "email" => $user->email,
            "password" => "password"
        ]);

        $updateData = [
            'name' => '',
            'email' => 'error-email'
        ];

        $response = $this->withHeader("Authorization", 'Bearer ' . $token["Authorization"]["token"])
        ->json('PUT', "/api/v1/user/{$user->id}", $updateData);

        $response->assertStatus(422)
        ->assertJsonStructure([
            'errors',
        ]);
    }

    public function test_delete_user_failure()
    {
        $user = User::factory()->create();
    
        $token = $this->postJson('/api/v1/user/login', [
            "email" => $user->email,
            "password" => "password"
        ]);
        
        $response = $this->withHeader("Authorization", 'Bearer ' . $token["Authorization"]["token"])
        ->json('DELETE', "/api/v1/user/0");

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => "Erro ao deletar o usuário!"
        ]);
    }

    public function test_logout_user_without_token()
    {
        $user = User::create([
            'name' => 'Usuário de teste',
            'email' => 'teste@teste.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->json('POST', "/api/v1/user/logout");

        $response->assertStatus(401)
        ->assertJson([
            'errors' => 'Token inválido ou ausente',
        ]);
    }
}
