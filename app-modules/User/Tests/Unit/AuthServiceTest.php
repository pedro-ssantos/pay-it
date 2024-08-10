<?php

namespace AppModules\User\Tests\Unit;

use Mockery;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use AppModules\User\Models\CommonUser;
use AppModules\User\Services\AuthService;
use AppModules\User\Database\Repositories\Interfaces\UserRepositoryInterface;



class AuthServiceTest extends TestCase
{
    protected $authService;
    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->authService = new AuthService($this->userRepository);
    }

    public function test_return_token_on_login_success()
    {
        $credentials = ['email' => 'test@example.com', 'password' => 'password'];

        $user = Mockery::mock(CommonUser::class)->shouldIgnoreMissing();
        $user->shouldReceive('createToken')->andReturn((object)['plainTextToken' => 'test_token']);
        $this->userRepository->shouldReceive('findByEmail')->with($credentials['email'])->andReturn($user);
        Hash::shouldReceive('check')->with($credentials['password'], $user->password)->andReturn(true);

        $result = $this->authService->login($credentials);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('test_token', $result['token']);
    }

    public function test_login_failure()
    {
        $credentials = ['email' => 'test@example.com', 'password' => 'password'];

        $this->userRepository->shouldReceive('findByEmail')->with($credentials['email'])->andReturn(null);

        $result = $this->authService->login($credentials);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Unauthorized', $result['message']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
