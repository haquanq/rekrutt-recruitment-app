<?php

namespace App\Modules\Auth\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Auth\Requests\UserLoginRequest;
use App\Modules\Auth\Resources\UserResource;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

#[Group(weight: 0)]
class AuthController extends BaseController
{
    /**
     * Login
     *
     * Return authenticated user with cookies.
     */
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->validated();
        if (!($token = Auth::attempt($credentials))) {
            // Invalid credentials
            return $this->unauthorizedResponse("Invalid credentials.");
        }

        $cookie = cookie("jwt_token", $token, config("jwt.refresh_ttl"), sameSite: "none", secure: true);
        return $this->okResponse(new UserResource(Auth::user()))->withCookie($cookie);
    }

    /**
     * Logout
     *
     * Return no content.
     */
    public function logout()
    {
        Auth::logout();
        return $this->noContentResponse();
    }

    /**
     * Refresh access token
     *
     * Return no content.
     */
    public function refresh(Request $request)
    {
        $token = Auth::refresh();
        $cookie = cookie("jwt_token", $token, config("jwt.refresh_ttl"), sameSite: "none", secure: true);
        return $this->noContentResponse()->withCookie($cookie);
    }

    /**
     * Get authenticated user (me)
     *
     * Return authenticated user.
     */
    public function me()
    {
        return $this->okResponse(new UserResource(Auth::user()));
    }
}
