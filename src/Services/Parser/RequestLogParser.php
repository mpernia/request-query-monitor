<?php

namespace RequestQueryMonitor\Services\Parser;

class RequestLogParser extends Parser
{
    protected function getDirectory(): string
    {
        return config('monitoring.requests.logs_file_path');
    }

    protected function setLogLines(string $level, array $context): array
    {
        return [
            'level' => $level,
            'method' => $context['method'],
            'url' => $context['url'],
            'response_size' => $context['response_size'],
            'duration_ms' => $context['duration_ms'],
            'ram' => $context['processor']['ram'] ?? 'unknown',
            'real_ram' => $context['processor']['real_ram'] ?? 'unknown',
        ];
    }

    public static function parse(string $filename): array
    {
        $instance = new self;
        $filePath = sprintf('%s%s%s', $instance->getDirectory(), DIRECTORY_SEPARATOR, $filename);
        return $instance->openLogFile($filePath)
            ->getLogLines();
    }
}
