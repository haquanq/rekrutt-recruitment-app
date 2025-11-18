<?php

use App\Modules\Auth\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource("users", UserController::class)->middleware("protected");
