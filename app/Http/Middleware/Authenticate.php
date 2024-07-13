<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request)
    {
        if (!$request->expectsJson() && !$request->hasHeader('Authorization')) {
            return response()->json(['error' => 'Token inválido ou ausente'], 401);
        }

        return null;
    }
}