<?php

use App\Modules\Auth\Controllers\AuthController;
use App\Modules\Auth\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "auth", "middleware" => "protected"], function () {
    Route::post("login", [AuthController::class, "login"])
        ->name("login")
        ->withoutMiddleware("protected");

    Route::post("refresh", [AuthController::class, "refresh"])
        ->name("refresh")
        ->withoutMiddleware("protected");

    Route::post("logout", [AuthController::class, "logout"]);
    Route::post("me", [AuthController::class, "me"]);
});

Route::controller(UserController::class)
    ->middleware("protected")
    ->prefix("users")
    ->group(function () {
        Route::get("/", "index");
        Route::get("/{id}", "show");
        Route::post("/", "store");
        Route::put("/{id}", "update");
        Route::delete("/{id}", "destroy");
        Route::patch("/{id}/status", "updateStatus");
    });
