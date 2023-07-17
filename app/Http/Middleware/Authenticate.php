<?php

namespace App\Http\Middleware;

use Closure;
use http\Env\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if(! auth()->check()){
            return response()->json([
                'error' => "Missing or invalid token"
            ], 401);
        }

        return $next($request);
    }
}
