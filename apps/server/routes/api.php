<?php

use App\Modules\Auth\Controllers\AuthController;
use App\Modules\Auth\Controllers\UserController;
use App\Modules\ContractType\Controllers\ContractTypeController;
use App\Modules\Department\Controllers\DepartmentController;
use App\Modules\EducationLevel\Controllers\EducationLevelController;
use App\Modules\ExperienceLevel\Controllers\ExperienceLevelController;
use App\Modules\HiringSource\Controllers\HiringSourceController;
use App\Modules\Position\Controllers\PositionController;
use App\Modules\RatingScale\Controllers\RatingScaleController;
use App\Modules\RatingScale\Controllers\RatingScalePointController;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function () {
    Route::post("login", [AuthController::class, "login"])->name("login");
    Route::post("refresh", [AuthController::class, "refresh"]);

    Route::middleware(["protected"])->group(function () {
        Route::post("logout", [AuthController::class, "logout"]);
        Route::post("me", [AuthController::class, "me"]);
    });
});

Route::middleware("protected")->group(function () {
    Route::apiResource("users", UserController::class)->parameters(["users" => "id"]);
    Route::apiResource("departments", DepartmentController::class)->parameters(["departments" => "id"]);
    Route::apiResource("positions", PositionController::class)->parameters(["positions" => "id"]);
    Route::apiResource("rating-scales", RatingScaleController::class)->parameters(["rating-scales" => "id"]);
    Route::apiResource("rating-scale-points", RatingScalePointController::class)->parameters([
        "rating-scale-points" => "id",
    ]);
    Route::apiResource("contract-types", ContractTypeController::class)->parameters(["contract-types" => "id"]);
    Route::apiResource("education-levels", EducationLevelController::class)->parameters(["education-levels" => "id"]);
    Route::apiResource("experience-levels", ExperienceLevelController::class)->parameters([
        "experience-levels" => "id",
    ]);
    Route::apiResource("hiring-sources", HiringSourceController::class)->parameters(["hiring-sources" => "id"]);
});
