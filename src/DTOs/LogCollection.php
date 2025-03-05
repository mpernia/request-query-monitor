<?php

namespace RequestQueryMonitor\DTOs;

class LogCollection
{
    protected array $headers = [];
    /** @var array LogItem */
    protected array $logs = [];

    public function addLog(LogItem $log): static
    {
        $this->logs[] = $log;
        return $this;
    }

    public function setHeaders(array $headers): static
    {
        $this->headers = $headers;
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    /** @return LogItem[] */
    public function all(): array
    {
        return $this->logs;
    }
}
