<?php

namespace AppModules\User\Routes\V1;

use Illuminate\Support\Facades\Route;
use AppModules\User\Http\Controllers\UserController;

Route::post('/users', [UserController::class, 'store']);
