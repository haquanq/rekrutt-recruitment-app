<?php

use App\Modules\Auth\Controllers\AuthController;
use App\Modules\Auth\Controllers\UserController;
use App\Modules\ContractType\Controllers\ContractTypeController;
use App\Modules\Department\Controllers\DepartmentController;
use App\Modules\Position\Controllers\PositionController;
use App\Modules\RatingScale\Controllers\RatingScaleController;
use App\Modules\RatingScale\Controllers\RatingScalePointController;
use App\Modules\RatingScale\Models\RatingScale;
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

Route::middleware("protected")->group(function () {
    Route::controller(UserController::class)
        ->prefix("users")
        ->group(function () {
            Route::get("/", "index");
            Route::get("/{id}", "show");
            Route::post("/", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
            Route::patch("/{id}/status", "updateStatus");
        });

    Route::controller(DepartmentController::class)
        ->prefix("departments")
        ->group(function () {
            Route::get("/", "index");
            Route::get("/{id}", "show");
            Route::post("/", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::controller(PositionController::class)
        ->prefix("positions")
        ->group(function () {
            Route::get("/", "index");
            Route::get("/{id}", "show");
            Route::post("/", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::controller(RatingScaleController::class)
        ->prefix("rating-scales")
        ->group(function () {
            Route::get("/", "index");
            Route::get("/{id}", "show");
            Route::post("/", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::controller(RatingScalePointController::class)
        ->prefix("rating-scale-points")
        ->group(function () {
            Route::get("/{id}", "show");
            Route::post("/", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::controller(ContractTypeController::class)
        ->prefix("contract-types")
        ->group(function () {
            Route::get("/", "index");
            Route::get("/{id}", "show");
            Route::post("/", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });
});
