<?php

namespace AppModules\Wallet\Http\Routes\V1;


use Illuminate\Support\Facades\Route;
use AppModules\Wallet\Http\Controllers\TransactionController;

Route::post('/transfer', [TransactionController::class, 'transfer'])->middleware(['auth:sanctum', 'ability:money-transfer']);
