<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Exceptions\InvalidStateException;

class SanctumErrorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (InvalidStateException $e) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }
    }
}
