<?php

namespace RequestQueryMonitor\Services\Viewer;

use RequestQueryMonitor\DTOs\LogCollection;
use RequestQueryMonitor\DTOs\LogItem;
use RequestQueryMonitor\Enums\LogType;
use RequestQueryMonitor\Services\Parser\{QueryLogParser, RequestLogParser};

final class CommandViewer
{
    protected LogCollection $logs;

    public function __construct(
        private readonly LogType $logType,
        private readonly string $filePath,
    )
    {
        $this->logs = new LogCollection;
        $this->readLogFile();
    }

    public static function render(LogType $logType, string $filePath): ?LogCollection
    {
        $instance = new self($logType, $filePath);
        return $instance->logs;
    }

    private function readLogFile(): void
    {
        $logs = $this->logType === LogType::REQUESTS
            ? RequestLogParser::parse($this->filePath)
            : QueryLogParser::parse($this->filePath);
        $this->setLogs($logs);
    }

    private function setLogs(array $logs): void
    {
        if ($this->logType === LogType::REQUESTS) {
            $this->logs->setHeaders(config('monitoring.requests.viewer.headers'));
            foreach ($logs as $log) {
                $allowProperties = $this->filterLogParse(
                    properties: config('monitoring.requests.viewer.properties'),
                    logParse: $log
                );
                $this->logs->addLog(LogItem::fromArray($allowProperties));
            }
        }
        if ($this->logType === LogType::QUERIES) {
            $this->logs->setHeaders(config('monitoring.queries.viewer.headers'));
            foreach ($logs as $log) {
                $allowProperties = $this->filterLogParse(
                    properties: config('monitoring.queries.viewer.properties'),
                    logParse: $log
                );
                $this->logs->addLog(LogItem::fromArray($allowProperties));
            }
        }
    }

    private function filterLogParse(array $properties, array $logParse): array
    {
        return array_filter(
            $logParse,
            fn($key) => in_array($key, $properties),
            ARRAY_FILTER_USE_KEY
        );
    }
}
