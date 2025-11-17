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

        if ($response instanceof JsonResponse && $response->getData()) {
            $payload = (array) $response->getData();

            $wrappedData = [
                "success" => $response->isSuccessful(),
                "status_code" => $response->getStatusCode(),
                "timestamp" => Carbon::now()->toISOString(),
                "request_id" => Str::uuid()->toString(),
            ];

            $isError = $response->status() >= 400;

            if ($isError) {
                $wrappedData["error"] = $payload;
            } elseif ($payload) {
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
