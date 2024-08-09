<?php

namespace AppModules\User\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use AppModules\User\Factories\UserFactory;
use AppModules\User\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = UserFactory::create($request->validated());

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    }
}
