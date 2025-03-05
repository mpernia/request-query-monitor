<?php

namespace RequestQueryMonitor\Services\Parser;

use InvalidArgumentException;
use RuntimeException;

abstract class Parser
{
    protected array $logLines = [];

    public function openLogFile(string $filePath): static
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException(
                sprintf('Log file does not exist: %s', $filePath)
            );
        }
        $file = fopen($filePath, 'r');
        if (!$file) {
            throw new RuntimeException(
                sprintf('Failed to open log file: %s', $filePath)
            );
        }
        while (($line = fgets($file)) !== false) {
            if (!preg_match('/\] (.*?)\.(INFO|WARNING):/', $line, $levelMatches)) {
                continue;
            }
            $level = $levelMatches[2] ?? 'UNKNOWN';
            $jsonStart = strpos($line, '{');
            if ($jsonStart === false) {
                continue;
            }
            $jsonPart = substr($line, $jsonStart);
            $context = json_decode($jsonPart, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                continue;
            }
            $this->logLines[] = $this->setLogLines($level, $context);
        }
        fclose($file);
        return $this;
    }

    public function getLogLines(): array
    {
        return $this->logLines;
    }

    abstract protected function getDirectory(): string;
    abstract protected function setLogLines(string $level, array $context): array;
    abstract public static function parse(string $filename): array;
}
