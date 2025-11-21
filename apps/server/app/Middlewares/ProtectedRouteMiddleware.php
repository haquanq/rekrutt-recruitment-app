<?php

namespace App\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProtectedRouteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie("jwt_token");

        if ($token !== null) {
            $request->headers->set("Authorization", "Bearer $token");
        }

        if (Auth::check() === false) {
            return response()->json(["message" => "Unauthorized."], 401);
        }

        return $next($request);
    }
}
