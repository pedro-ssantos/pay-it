<?php

namespace AppModules\User\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use AppModules\User\Factories\UserFactory;
use AppModules\User\Http\Requests\StoreUserRequest;

class UserController extends Controller
{

    public function __construct(protected UserFactory $userFactory) {}

    public function store(StoreUserRequest $request): JsonResponse
    {

        return response()->json([
            'message' => 'User created successfully',
            'user' =>'user',
        ], 201);
    }
}
