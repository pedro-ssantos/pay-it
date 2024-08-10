<?php

namespace AppModules\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateUserFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_common_user_via_post_request()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret12345',
            'cpf' => '12345678901',
        ];

        $response = $this->postJson('/api/v1/users', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'User created successfully',
                'user' => [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'cpf' => '12345678901',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'type' => 'common_user',
            'cpf' => '12345678901',
        ]);
    }

    public function test_it_creates_a_merchant_user_via_post_request()
    {
        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'secret12345',
            'cnpj' => '12345678901234',
        ];

        $response = $this->postJson('/api/v1/users', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'User created successfully',
                'user' => [
                    'name' => 'Jane Doe',
                    'email' => 'jane@example.com',
                    'cnpj' => '12345678901234',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'type' => 'merchant_user',
            'cnpj' => '12345678901234',
        ]);
    }

    public function test_it_validates_cpf_and_cnpj()
    {
        $data = [
            'name' => 'Invalid User',
            'email' => 'invalid@example.com',
            'password' => 'secret12345',
        ];

        $response = $this->postJson('/api/v1/users', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cpf', 'cnpj']);
    }
}
