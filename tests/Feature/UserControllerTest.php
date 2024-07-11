<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_register_user()
    {
        $data = [
            'name' => 'Usuário de teste',
            'email' => 'teste@teste.com',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/v1/users', $data);

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
}
