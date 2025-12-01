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

        if ($response instanceof JsonResponse && $request->is("api/*")) {
            if ($response->status() === 204) {
                return $response;
            }

            $wrappedData = [
                "success" => $response->isSuccessful(),
                "status_code" => $response->getStatusCode(),
                "timestamp" => Carbon::now()->toISOString(),
                "request_id" => Str::uuid()->toString(),
            ];

            $payload = (array) $response->getData();

            if (isset($payload["data"])) {
                $wrappedData = [...$wrappedData, ...$payload];
            } elseif ($response->status() >= 400) {
                $wrappedData["error"] = $payload;
            } else {
                $wrappedData["data"] = $payload;
            }

            $response->setData(ArrayHelper::convertKeysToCamelCase($wrappedData));
        }

        return $response;
    }
}
