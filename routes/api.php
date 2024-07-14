<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/user', [UserController::class, 'register']);
    Route::post('/user/login', [UserController::class, 'login'])->name('login');
    Route::post('/user/logout', [UserController::class, 'logout']);

    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::get('/user/me', [UserController::class, 'getUserInfo']);
    Route::delete('/user/{id}', [UserController::class, 'delete']);
});