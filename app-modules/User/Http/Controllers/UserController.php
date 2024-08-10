<?php

namespace AppModules\User\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use AppModules\User\Factories\UserFactory;
use AppModules\User\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;


class UserController extends Controller
{

    public function __construct(protected UserFactory $userFactory) {}

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userFactory->create($request->validated());

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user->only(['name', 'email', 'cpf','cnpj']),
        ], 201);
    }
}
