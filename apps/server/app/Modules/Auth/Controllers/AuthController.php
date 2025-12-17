<?php

namespace App\Modules\Auth\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Auth\Models\User;
use App\Modules\Auth\Requests\UserLoginRequest;
use App\Modules\Auth\Resources\UserResource;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

#[Group(weight: 0)]
class AuthController extends BaseController
{
    /**
     * Login
     *
     * Return authenticated user with cookies.
     *
     * Test accounts (password: 12345678)
     * - manager@gmail.com (general manager not hiring manager)
     * - recruiter@gmail.com
     * - hiring.manager@gmail.com
     * - executive@gmail.com
     */
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->validated();
        $user = User::where("email", $credentials["email"])->first();

        if (!$user || !Hash::check($credentials["password"], $user->password)) {
            // Invalid credentials
            return $this->unauthorizedResponse("Invalid credentials.");
        }

        $token = $user->createToken("api_token")->plainTextToken;
        $cookie = cookie(
            name: "api_token",
            value: $token,
            minutes: config("sanctum.expiration"),
            sameSite: "strict",
            secure: true,
            httpOnly: true,
        );
        Auth::setUser($user);
        return $this->okResponse(new UserResource(Auth::user()))->withCookie($cookie);
    }

    /**
     * Logout
     *
     * Return no content.
     */
    public function logout()
    {
        Auth::user()->tokens()->delete();
        return $this->noContentResponse();
    }

    /**
     * Get authenticated user
     *
     * Return authenticated user.
     */
    public function me()
    {
        return $this->okResponse(new UserResource(Auth::user()));
    }
}
