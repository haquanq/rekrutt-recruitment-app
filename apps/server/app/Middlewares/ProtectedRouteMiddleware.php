<?php

namespace App\Middlewares;

use App\Modules\Auth\Enums\UserStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProtectedRouteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() === false) {
            return response()->json(["message" => "Unauthorized."], Response::HTTP_UNAUTHORIZED);
        }

        if (Auth::user()->status !== UserStatus::ACTIVE) {
            return response()->json(["message" => "Your account is no longer active."], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
