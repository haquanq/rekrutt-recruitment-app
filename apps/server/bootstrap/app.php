<?php

use App\Middlewares\GlobalResponseWrapperMiddleware;
use App\Middlewares\CookieMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\QueryBuilder\Exceptions\InvalidQuery as InvalidQueryException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withCommands([...glob(app_path("Modules/*/Commands"))])
    ->withRouting(api: __DIR__ . "/../routes/api.php", commands: __DIR__ . "/../routes/console.php", health: "/up")
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(CookieMiddleware::class);
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
                $modelClass = $exception->getPrevious()->getModel();
                $modelName = class_basename($modelClass);
                return response()->json(
                    [
                        "message" => "The requested {$modelName} was not found.",
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

            if ($exception instanceof ConflictHttpException) {
                return response()->json(
                    [
                        "message" => $exception->getMessage(),
                    ],
                    Response::HTTP_CONFLICT,
                );
            }

            if ($exception instanceof AuthenticationException) {
                return response()->json(
                    [
                        "message" => $exception->getMessage(),
                    ],
                    Response::HTTP_UNAUTHORIZED,
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
