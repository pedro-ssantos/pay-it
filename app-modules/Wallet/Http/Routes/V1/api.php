<?php

namespace AppModules\Wallet\Http\Routes\V1;


use Illuminate\Support\Facades\Route;
use AppModules\Wallet\Http\Controllers\TransactionController;

Route::middleware('auth:sanctum')->post('/transfer', [TransactionController::class, 'transfer']);
