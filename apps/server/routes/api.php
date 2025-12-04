<?php

use App\Modules\Auth\Controllers\AuthController;
use App\Modules\Auth\Controllers\UserController;
use App\Modules\Candidate\Controllers\CandidateController;
use App\Modules\Candidate\Controllers\CandidateDocumentController;
use App\Modules\Candidate\Controllers\CandidateExperienceController;
use App\Modules\ContractType\Controllers\ContractTypeController;
use App\Modules\Department\Controllers\DepartmentController;
use App\Modules\EducationLevel\Controllers\EducationLevelController;
use App\Modules\ExperienceLevel\Controllers\ExperienceLevelController;
use App\Modules\HiringSource\Controllers\HiringSourceController;
use App\Modules\Interview\Controllers\InterviewMethodController;
use App\Modules\Position\Controllers\PositionController;
use App\Modules\Proposal\Controllers\ProposalController;
use App\Modules\Proposal\Controllers\ProposalDocumentController;
use App\Modules\RatingScale\Controllers\RatingScaleController;
use App\Modules\RatingScale\Controllers\RatingScalePointController;
use App\Modules\Recruitment\Controllers\RecruitmentController;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post("login", "login")->name("login");
        Route::post("refresh", "refresh");
    });

    Route::middleware(["protected"])
        ->controller(AuthController::class)
        ->group(function () {
            Route::post("logout", "logout");
            Route::post("me", "me");
        });
});

Route::middleware("protected")->group(function () {
    Route::prefix("users")
        ->controller(UserController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::put("/{id}", "update");
            Route::post("/{id}/suspend", "suspend");
            Route::post("/{id}/retire", "retire");
            Route::post("/{id}/activate", "activate");
            Route::delete("/{id}", "destroy");
        });

    Route::prefix("departments")
        ->controller(DepartmentController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::prefix("positions")
        ->controller(PositionController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::prefix("rating-scales")
        ->controller(RatingScaleController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::prefix("rating-scale-points")
        ->controller(RatingScalePointController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::prefix("contract-types")
        ->controller(ContractTypeController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::prefix("experience-levels")
        ->controller(ExperienceLevelController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::prefix("education-levels")
        ->controller(EducationLevelController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::prefix("hiring-source")
        ->controller(HiringSourceController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::prefix("candidates")
        ->controller(CandidateController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::prefix("candidate-documents")
        ->controller(CandidateDocumentController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::prefix("candidate-experiences")
        ->controller(CandidateExperienceController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::prefix("proposals")
        ->controller(ProposalController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::put("/{id}", "update");
            Route::post("/{id}/submit", "submit");
            Route::post("/{id}/reject", "reject");
            Route::post("/{id}/approve", "approve");
            Route::delete("/{id}", "destroy");
        });

    Route::prefix("proposal-documents")
        ->controller(ProposalDocumentController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::patch("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::prefix("interview-methods")
        ->controller(InterviewMethodController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });

    Route::prefix("recruitments")
        ->controller(RecruitmentController::class)
        ->group(function () {
            Route::get("", "index");
            Route::get("/{id}", "show");
            Route::post("", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
            Route::post("/{id}/publish", "publish");
            Route::post("/{id}/close", "close");
        });
});
