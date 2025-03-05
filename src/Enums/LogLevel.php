<?php

namespace RequestQueryMonitor\Enums;

enum LogLevel: string
{
    case WARNING = "warning";
    case INFO = "info";
    case ERROR = "error";
    case CRITICAL = "critical";
}
