<?php

use App\Modules\Auth\Controllers\AuthController;
use App\Modules\Auth\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "auth", "middleware" => "protected"], function () {
    Route::post("login", [AuthController::class, "login"])
        ->name("login")
        ->withoutMiddleware("protected");

    Route::post("logout", [AuthController::class, "logout"]);
    Route::post("refresh", [AuthController::class, "refresh"]);
    Route::post("me", [AuthController::class, "me"]);
});

Route::apiResource("users", UserController::class)->middleware("protected");
