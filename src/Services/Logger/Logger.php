<?php

namespace RequestQueryMonitor\Services\Logger;

use RequestQueryMonitor\Enums\LogLevel;

abstract class Logger
{
    protected const FLOAT_PRECISION = 2;
    protected const HASH_LENGTH = 15;
    protected LogLevel $level = LogLevel::INFO;
    protected int $errorThreshold = 500;
    protected string $filename = 'query-monitor';
    protected ?string $channel = null;
    protected string $fingerprint = '';
    protected bool $useMemory = false;

    public function setErrorThreshold(int $errorThreshold): void
    {
        $this->errorThreshold = $errorThreshold;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    public function setFingerprint(bool $singleFile): void
    {
        $this->fingerprint = $singleFile ? $this->createFingerprint() : '';
    }

    public function setMemoryUsage(bool $usage): void
    {
        $this->useMemory = $usage;
    }

    protected function getMemoryUsage(): array
    {
        return [
            'processor' => [
                'ram' => $this->formatBytes(memory_get_usage()),
                'real_ram' => $this->formatBytes(memory_get_usage(true))
            ]
        ];
    }

    protected function createFingerprint(): string
    {
        $hash = sprintf(
            '%s-%s',
            uniqid(prefix: '', more_entropy: true),
            random_bytes(8)
        );
        return substr(md5($hash), 0, self::HASH_LENGTH);
    }

    protected function formatBytes(int $bytes): string
    {
        return match (true) {
            $bytes < 1024 => sprintf('%d bytes', $bytes),
            $bytes < 1048576 => sprintf('%.2f KB', round(num: $bytes / 1024, precision: self::FLOAT_PRECISION)),
            default => sprintf('%.2f MB', round(num: $bytes / 1048576, precision: self::FLOAT_PRECISION)),
        };
    }

    protected function getFingerprint(): string
    {
        return !empty($this->fingerprint)
            ? sprintf('_%s', $this->fingerprint)
            : $this->fingerprint;
    }

    protected function getLevel(int $Threshold): LogLevel
    {
        $this->level = $Threshold > $this->errorThreshold ? LogLevel::WARNING : LogLevel::INFO;
        return $this->level;
    }

    protected function writeLog(string $message, array $context): void
    {
        if ($this->useMemory) {
            $context = array_merge($context, $this->getMemoryUsage());
        }
        $filePath = sprintf(
            '%s%s%s-%s%s.log',
            $this->getDirectory(),
            DIRECTORY_SEPARATOR,
            $this->filename,
            date('Y-m-d'),
            $this->getFingerprint()
        );
        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        $contextJson = !empty($context) ? json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '';
        $content = sprintf(
            '[%s] %s.%s: %s %s %s',
            date('Y-m-d H:i:s'),
            $this->channel ?? 'local',
            strtoupper($this->level->value),
            $message,
            $contextJson,
            PHP_EOL
        );
        file_put_contents($filePath, $content, FILE_APPEND);
    }

    public abstract function saveLog(...$params): void;

    abstract protected function getDirectory(): string;
}
