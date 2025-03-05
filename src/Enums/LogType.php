<?php

namespace RequestQueryMonitor\Enums;

enum LogType: string
{
    case QUERIES = 'queries';
    case REQUESTS = 'requests';
}
