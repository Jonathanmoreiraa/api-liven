<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/user', [UserController::class, 'register']);
    Route::post('/user/login', [UserController::class, 'login'])->name('login');
    
    Route::put('/user/{id}', [UserController::class, 'update']);
});