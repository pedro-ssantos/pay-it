<?php

namespace AppModules\User\Tests\Feature;

use Mockery;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use AppModules\User\Models\CommonUser;
use AppModules\User\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use AppModules\User\Database\Factories\CommonUserFactory;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_success()
    {
        $user = CommonUser::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password12345'),
            'cpf' => '12345678901',
        ]);

        $credentials = ['email' => 'test@example.com', 'password' => 'password12345'];

        $response = $this->postJson('/api/v1/login', $credentials);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function test_login_fail_with_wrong_password()
    {
        $user = CommonUser::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password12345'),
            'cpf' => '12345678901',
        ]);

        $credentials = ['email' => 'test@example.com', 'password' => 'password'];

        $response = $this->postJson('/api/v1/login', $credentials);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthorized']);
        $response->assertJsonMissing(['token']);
    }

    public function test_login_failure()
    {
        $credentials = ['email' => 'test@example.com', 'password' => 'wrongpassword'];

        $response = $this->postJson('/api/v1/login', $credentials);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthorized']);
    }
}
