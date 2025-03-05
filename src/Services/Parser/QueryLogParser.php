<?php

namespace RequestQueryMonitor\Services\Parser;

final class QueryLogParser extends Parser
{
    protected function getDirectory(): string
    {
        return config('monitoring.queries.logs_file_path');
    }

    protected function setLogLines(string $level, array $context): array
    {
        return [
            'level' => $level,
            'class' => $context['class'] ?? 'unknown',
            'method' => $context['method'] ?? 'unknown',
            'time_ms' => $context['time_ms'] ?? 0.0,
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
