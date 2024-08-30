<?php

namespace AppModules\Transaction\Http\Routes\V1;

use Illuminate\Support\Facades\Route;
use AppModules\Transaction\Http\Controllers\TransactionController;

Route::post('/transfer', [TransactionController::class, 'transfer'])->middleware(['auth:sanctum', 'ability:money-transfer']);
