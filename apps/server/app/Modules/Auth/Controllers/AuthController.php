<?php

namespace App\Modules\Auth\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Auth\Requests\ChangePasswordRequest;
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

        return $this->okResponse([
            "user" => new UserResource(Auth::user()),
            "token" => $token,
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return $this->noContentResponse();
    }

    public function refresh()
    {
        return $this->okResponse([
            "user" => new UserResource(Auth::user()),
            "token" => Auth::refresh(),
        ]);
    }

    public function me()
    {
        return $this->okResponse(new UserResource(Auth::user()));
    }
}
