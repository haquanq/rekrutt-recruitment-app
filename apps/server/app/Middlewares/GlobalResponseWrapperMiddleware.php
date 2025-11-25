<?php

namespace App\Middlewares;

use App\Helpers\ArrayHelper;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class GlobalResponseWrapperMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $wrappedData = [
                "success" => $response->isSuccessful(),
                "status_code" => $response->getStatusCode(),
                "timestamp" => Carbon::now()->toISOString(),
                "request_id" => Str::uuid()->toString(),
            ];

            $payload = (array) $response->getData();

            $isError = $response->status() >= 400;

            if ($isError) {
                $wrappedData["error"] = $payload;
            } else {
                $wrappedData["data"] = $payload;
            }

            $noContent = $response->status() === 204;

            if (!$noContent) {
                $response->setData(ArrayHelper::convertKeysToCamelCase($wrappedData));
            }
        }

        return $response;
    }
}
