<?php

namespace App\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CookieMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie("api_token");

        if (Str::trim($request->header("Authorization")) === "Bearer" && $token) {
            $request->headers->set("Authorization", "Bearer $token");
        }

        return $next($request);
    }
}
