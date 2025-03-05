<?php

namespace RequestQueryMonitor\Commands;

use Illuminate\Console\Command;
use RequestQueryMonitor\DTOs\LogCollection;
use RequestQueryMonitor\Enums\{LogLevel, LogType};
use RequestQueryMonitor\Services\Viewer\{CommandValidator, CommandViewer};

class ShowLogCommand extends Command
{
    protected $signature = 'show:log
                            {--requests}
                            {--queries}
                            {--file= : The log file name (required)}';

    protected $description = 'Show logs (request or query) from a specified log file';

    public function handle(): int
    {
        $requestFlag = $this->option('requests');
        $queriesFlag = $this->option('queries');
        $logFile = $this->option('file');
        $errors = CommandValidator::validate(
            requestFlag: $requestFlag,
            queriesFlag: $queriesFlag,
            file: $logFile
        );
        if ($errors) {
            foreach ($errors as $error) {
                $this->error($error);
            }
            return Command::FAILURE;
        }
        $logType = $requestFlag ? LogType::REQUESTS : LogType::QUERIES;
        $logs = CommandViewer::render(logType: $logType, filePath: $logFile);
        $this->displayLogs($logs);
        return Command::SUCCESS;
    }

    protected function displayLogs(LogCollection $collection): void
    {
        $rows = [];
        foreach ($collection->all() as $log) {
            $row = $log->toArray();
            if (key_exists('level', $row)) {
                $logLevel = LogLevel::from(strtolower($row['level']));
                $level = match ($logLevel) {
                    LogLevel::WARNING => $this->getFormattedLevel($logLevel, 'yellow'),
                    LogLevel::INFO => $this->getFormattedLevel($logLevel, 'blue'),
                    default => $this->getFormattedLevel($logLevel, 'red'),
                };
                $row['level'] = $level;
            }
            $rows[] = $row;
        }
        $this->table($collection->getHeaders(), $rows);
    }

    protected function getFormattedLevel(LogLevel $logLevel, string $color): string
    {
        $level = strtoupper($logLevel->value);
        return match ($color) {
            'yellow' => "<fg=yellow>$level</>",
            'blue' => "<fg=blue>$level</>",
            'red' => "<fg=red>$level</>",
            default => $level,
        };
    }
}
