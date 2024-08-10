<?php

namespace AppModules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use AppModules\User\Services\AuthService;
use AppModules\User\Http\Requests\LoginRequest;

class AuthController extends Controller
{

    public function __construct(protected AuthService $authService) {}

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        // Use the AuthService to handle the login logic
        $result = $this->authService->login($credentials);

        if ($result['status'] === 'error') {
            return response()->json(['message' => $result['message']], 401);
        }

        return response()->json(['token' => $result['token']], 200);
    }
}
