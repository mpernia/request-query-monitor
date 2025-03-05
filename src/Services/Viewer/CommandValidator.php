<?php

namespace RequestQueryMonitor\Services\Viewer;

class CommandValidator
{
    public static function validate(
        string|bool|null $requestFlag,
        string|bool|null $queriesFlag,
        string|bool|null $file
    ): array
    {
        $errors = [];
        if ($requestFlag && $queriesFlag) {
            $errors[] = 'You can only pass one flag: --requests or --queries.';
        }
        if (!$requestFlag && !$queriesFlag) {
            $errors[] = 'You must pass either --requests or --queries.';
        }
        if (!$file) {
            $errors[] = 'The log file name is required.';
        }
        return $errors;
    }
}
