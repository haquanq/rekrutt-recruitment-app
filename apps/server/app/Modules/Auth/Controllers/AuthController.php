<?php

namespace App\Modules\Auth\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (!($token = Auth::attempt($credentials))) {
            return $this->unauthorizedResponse("Invalid credentials.");
        }

        $cookie = cookie("jwt_token", $token, config("jwt.refresh_ttl"), sameSite: "none", secure: true);
        return $this->okResponse(new UserResource(Auth::user()))->withCookie($cookie);
    }

    public function logout()
    {
        Auth::logout();
        return $this->noContentResponse();
    }

    public function refresh(Request $request)
    {
        $token = Auth::refresh();
        $cookie = cookie("jwt_token", $token, config("jwt.refresh_ttl"), sameSite: "none", secure: true);
        return $this->noContentResponse()->withCookie($cookie);
    }

    public function me()
    {
        return $this->okResponse(new UserResource(Auth::user()));
    }
}
