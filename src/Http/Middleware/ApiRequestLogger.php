<?php

namespace RequestQueryMonitor\Http\Middleware;

use Closure;
use Illuminate\Http\{Request, Response, JsonResponse, RedirectResponse};
use Symfony\Component\HttpFoundation\{BinaryFileResponse, StreamedResponse};
use RequestQueryMonitor\Services\Logger\RequestLogger;

final class ApiRequestLogger
{
    private const MILLISECONDS = 1000;
    private RequestLogger $requestLogger;

    public function __construct(RequestLogger $requestLogger)
    {
        $this->requestLogger = $requestLogger;
    }

    public function handle(Request $request, Closure $next): Response|JsonResponse|RedirectResponse|BinaryFileResponse|StreamedResponse
    {
        $startTime = microtime(true);
        /** @var Response|JsonResponse|JsonResponse|BinaryFileResponse|StreamedResponse $response */
        $response = $next($request);
        if (!config('monitoring.requests.enabled', false)) {
            return $response;
        }
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * self::MILLISECONDS;
        if (!($response instanceof BinaryFileResponse || $response instanceof StreamedResponse)) {
            $this->requestLogger->log($request, $response, $duration);
        }
        return $response;
    }
}
