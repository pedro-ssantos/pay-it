<?php

namespace AppModules\User\Services;

use Illuminate\Support\Facades\Hash;
use AppModules\User\Database\Repositories\Interfaces\UserRepositoryInterface;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(array $credentials)
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return ['status' => 'error', 'message' => 'Unauthorized'];
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return ['status' => 'success', 'token' => $token];
    }
}
