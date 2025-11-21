<?php

use Illuminate\Support\Facades\Route;
use Presentation\User\Controllers\UserController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [UserController::class, 'logout']);
        Route::get('/user', [UserController::class, 'get']);
    });
});
