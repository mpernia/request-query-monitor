<?php

namespace RequestQueryMonitor\Services\Logger;

use Illuminate\Http\{Request, Response, JsonResponse, RedirectResponse};
use InvalidArgumentException;

final class RequestLogger extends Logger
{
    public function __construct()
    {
        $this->setErrorThreshold(
            config('monitoring.requests.slow_request_threshold', 500)
        );
        $this->setChannel(config('app.env'));
        $this->setFilename(
            config('monitoring.requests.request_log_filename', 'request-monitor')
        );
        $this->setMemoryUsage(
            config('monitoring.requests.enabled_memory_usage', false)
        );
        $this->setFingerprint(
            config('monitoring.requests.enabled_single_file', false)
        );
    }

    public function log(Request $request, Response|JsonResponse|RedirectResponse $response, float $duration): void
    {
        $this->getLevel($duration);
        $responseSize = strlen($response->getContent());
        $context = [
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'status' => $response->getStatusCode(),
            'response_size' => $this->formatBytes($responseSize),
            'duration_ms' => number_format($duration, self::FLOAT_PRECISION),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'payload' => $this->sanitizePayload($request),
        ];
        $message = $request->expectsJson() ? 'API Request' : 'WEB Request';
        $this->saveLog($message, $context);
    }

    private function sanitizePayload(Request $request): array
    {
        $payload = $request->all();
        unset($payload['password'], $payload['password_confirmation']);
        return $payload;
    }

    public function saveLog(...$params): void
    {
        if (count($params) === 0) {
            throw new InvalidArgumentException('Invalid log parameters, must not be empty.');
        }
        $message = (string) $params[0];
        $context = is_array($params[1]) ? $params[1] : [$params[1]];
        $this->writeLog(message: $message, context: $context);
    }

    protected function getDirectory(): string
    {
        return config('monitoring.requests.logs_file_path');
    }
}
