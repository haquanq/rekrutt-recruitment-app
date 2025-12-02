<?php

use App\Middlewares\GlobalResponseWrapperMiddleware;
use App\Middlewares\JWTCookieMiddleware;
use App\Middlewares\ProtectedRouteMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\QueryBuilder\Exceptions\InvalidQuery as InvalidQueryException;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(api: __DIR__ . "/../routes/api.php", commands: __DIR__ . "/../routes/console.php", health: "/up")
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(JWTCookieMiddleware::class);
        $middleware->alias(["protected" => ProtectedRouteMiddleware::class]);
        $middleware->append(GlobalResponseWrapperMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $exception) {
            if ($exception->getPrevious() instanceof AuthorizationException) {
                return response()->json(
                    [
                        "message" => $exception->getMessage(),
                    ],
                    Response::HTTP_FORBIDDEN,
                );
            }

            if ($exception->getPrevious() instanceof ModelNotFoundException) {
                return response()->json(
                    [
                        "message" => "Resource not found",
                    ],
                    Response::HTTP_NOT_FOUND,
                );
            }

            if ($exception instanceof InvalidQueryException) {
                return response()->json(
                    [
                        "message" => $exception->getMessage(),
                    ],
                    Response::HTTP_BAD_REQUEST,
                );
            }

            if ($exception instanceof QueryException) {
                return response()->json(
                    [
                        "message" => $exception->getMessage(),
                    ],
                    Response::HTTP_BAD_REQUEST,
                );
            }

            // return response()->json(
            //     [
            //         "message" => "Oops, something wrong happened!",
            //     ],
            //     Response::HTTP_INTERNAL_SERVER_ERROR,
            // );
        });
    })
    ->create();
