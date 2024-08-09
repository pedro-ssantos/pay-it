<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return response()->json(['status' => 'ok']);
});
