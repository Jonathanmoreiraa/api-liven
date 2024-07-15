<?php

use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/user', [UserController::class, 'register']);
    Route::post('/user/login', [UserController::class, 'login'])->name('login');
    Route::post('/user/refresh', [UserController::class, 'refresh']);
    Route::post('/user/logout', [UserController::class, 'logout']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::get('/user/me', [UserController::class, 'getUserInfo']);
    Route::delete('/user/{id}', [UserController::class, 'delete']);

    Route::post('/user/address', [AddressController::class, 'add']);
    Route::get('/user/address', [AddressController::class, 'getAdresseses']);
    Route::get('/user/address/{id}', [AddressController::class, 'getAdressById']);
    Route::put('/user/address/{id}', [AddressController::class, 'update']);
    Route::delete('/user/address/{id}', [AddressController::class, 'delete']);
});