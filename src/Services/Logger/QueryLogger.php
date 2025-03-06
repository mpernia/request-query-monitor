<?php

namespace RequestQueryMonitor\Services\Logger;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RequestQueryMonitor\Enums\LogLevel;

final class QueryLogger extends Logger
{
    protected const EXCLUDED_NAMESPACES = ['Illuminate\\', 'RequestQueryMonitor\\'];

    public function __construct()
    {
        $this->setErrorThreshold(
            config('monitoring.queries.slow_query_threshold', 100)
        );
        $this->setChannel(config('app.env'));
        $this->setFilename(
            config('monitoring.queries.query_log_filename', 'query-monitor')
        );
        $this->setMemoryUsage(
            config('monitoring.queries.enabled_memory_usage', false)
        );
        $this->setFingerprint(
            config('monitoring.queries.enabled_single_file', false)
        );
    }

    public function register(): void
    {
        $enabled = config('monitoring.queries.enabled', false);
        if (!$enabled) {
            return;
        }
        DB::listen(function ($query) {
            $this->logQuery($query->sql, $query->bindings, $query->time);
        });
    }

    private function logQuery(string $sql, array $bindings, float $time): void
    {
        $fullQuery = $this->interpolateQuery($sql, $bindings);
        $description = $this->getLevel($time) === LogLevel::WARNING
            ? 'Slow query detected'
            : 'Query executed';
        $caller = $this->getCallerContext();
        $this->saveLog(
            $description,
            ['query' => $fullQuery, 'time_ms' => $time, 'class' => $caller['class'], 'method' => $caller['method']]
        );
    }

    private function interpolateQuery(string $query, array $bindings): string
    {
        $pdo = DB::getPdo();
        foreach ($bindings as $i => $binding) {
            if (is_numeric($binding)) {
                $bindings[$i] = $binding;
            } else {
                $bindings[$i] = $pdo->quote($binding);
            }
        }
        return vsprintf(str_replace('?', '%s', $query), $bindings);
    }

    protected function getCallerContext(): array
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $excludedNamespaces = array_merge(self::EXCLUDED_NAMESPACES, config('monitoring.queries.excluded_namespaces'));
        foreach ($trace as $frame) {
            $class = $frame['class'] ?? null;
            $isExcluded = false;
            if (!is_null($class)) {
                foreach ($excludedNamespaces as $namespace) {
                    if (str_starts_with($class, $namespace)) {
                        $isExcluded = true;
                        break;
                    }
                }
            }
            if ($isExcluded) {
                continue;
            }
            return [
                'class' => $class ?? 'unknown',
                'method' => $frame['function'] ?? 'unknown',
                'file' => $frame['file'] ?? 'unknown',
                'line' => $frame['line'] ?? 'unknown',
            ];
        }
        return ['class' => 'unknown', 'method' => 'unknown'];
    }


    public function saveLog(...$params): void
    {
        if (count($params) === 0) {
            throw new InvalidArgumentException('Invalid log parameters, must not be empty.');
        }
        if (!is_string($params[0])){
            throw new InvalidArgumentException('Invalid log parameters, the first input parameter must be a string');
        }
        $message = (string) $params[0];
        $context = is_array($params[1]) ? $params[1] : [$params[1]];
        $this->writeLog(message: $message, context: $context);
    }

    protected function getDirectory(): string
    {
        return config('monitoring.queries.logs_file_path');
    }
}
