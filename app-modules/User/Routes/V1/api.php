<?php

namespace AppModules\User\Routes\V1;

use AppModules\User\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use AppModules\User\Http\Controllers\UserController;

Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/usersss', function (Request $request) {
    return response()->json(['debug' => 'foobar222']);
});
