<?php

namespace RequestQueryMonitor\Http\Middleware;

use Closure;
use Illuminate\Http\{JsonResponse, Request, Response};
use RequestQueryMonitor\Services\Logger\RequestLogger;

final class ApiRequestLogger
{
    private const MILLISECONDS = 1000;
    private RequestLogger $requestLogger;

    public function __construct(RequestLogger $requestLogger)
    {
        $this->requestLogger = $requestLogger;
    }

    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $startTime = microtime(true);
        /** @var Response|JsonResponse $response */
        $response = $next($request);
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * self::MILLISECONDS;
        $this->requestLogger->log($request, $response, $duration);
        return $response;
    }
}
