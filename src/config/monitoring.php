<?php

return [
    'queries' => [
        /*
        |--------------------------------------------------------------------------
        | Query Logs File Path
        |--------------------------------------------------------------------------
        |
        | Path to the log file for query monitoring.
        |
        */
        'logs_file_path' => base_path('storage/logs/monitoring/queries'),

        /*
        |--------------------------------------------------------------------------
        | Excluded Namespaces
        |--------------------------------------------------------------------------
        |
        | Namespaces to exclude from query logging.
        |
        */
        'excluded_namespaces' => [],

        /*
        |--------------------------------------------------------------------------
        | Enable Query Logging
        |--------------------------------------------------------------------------
        |
        | Enable or disable query logging.
        |
        */
        'enabled' => env('QUERY_LOGGER_ENABLED', false),

        /*
        |--------------------------------------------------------------------------
        | Enable Memory Usage Logging
        |--------------------------------------------------------------------------
        |
        | Enable memory usage logging for queries.
        |
        */
        'enabled_memory_usage' => env('QUERY_LOGGER_ENABLED_MEMORY_USAGE', false),

        /*
        |--------------------------------------------------------------------------
        | Enable Single File Logging
        |--------------------------------------------------------------------------
        |
        | Enable logging to a single file.
        |
        */
        'enabled_single_file' => env('QUERY_LOGGER_ENABLED_SINGLE_FILE', false),

        /*
        |--------------------------------------------------------------------------
        | Slow Query Threshold
        |--------------------------------------------------------------------------
        |
        | Threshold for slow queries in milliseconds.
        |
        */
        'slow_query_threshold' => env('QUERY_LOGGER_SLOW_THRESHOLD', 100),

        /*
        |--------------------------------------------------------------------------
        | Query Log Filename
        |--------------------------------------------------------------------------
        |
        | Filename for the query log.
        |
        */
        'query_log_filename' => env('QUERY_LOGGER_FILENAME', 'query-monitor'),

        'viewer' => [
            /*
            |--------------------------------------------------------------------------
            | Query Log Viewer Headers
            |--------------------------------------------------------------------------
            |
            | Headers for the query log viewer.
            |
            */
            'headers' => ['Level', 'Class', 'Method', 'Duration (ms)', 'RAM', 'Real RAM'],

            /*
            |--------------------------------------------------------------------------
            | Query Log Viewer Properties
            |--------------------------------------------------------------------------
            |
            | Properties to display in the query log viewer.
            |
            */
            'properties' => ['level', 'class', 'method', 'time_ms', 'ram', 'real_ram'],
        ]
    ],

    'requests' => [
        /*
        |--------------------------------------------------------------------------
        | Request Logs File Path
        |--------------------------------------------------------------------------
        |
        | Path to the log file for request monitoring.
        |
        */
        'logs_file_path' => base_path('storage/logs/monitoring/requests'),

        /*
        |--------------------------------------------------------------------------
        | Enable Request Logging
        |--------------------------------------------------------------------------
        |
        | Enable or disable request logging.
        |
        */
        'enabled' => env('REQUEST_LOGGER_ENABLED', false),

        /*
        |--------------------------------------------------------------------------
        | Enable Memory Usage Logging
        |--------------------------------------------------------------------------
        |
        | Enable memory usage logging for requests.
        |
        */
        'enabled_memory_usage' => env('REQUEST_LOGGER_ENABLED_MEMORY_USAGE', false),

        /*
        |--------------------------------------------------------------------------
        | Enable Single File Logging
        |--------------------------------------------------------------------------
        |
        | Enable logging to a single file.
        |
        */
        'enabled_single_file' => env('REQUEST_LOGGER_ENABLED_SINGLE_FILE', false),

        /*
        |--------------------------------------------------------------------------
        | Slow Request Threshold
        |--------------------------------------------------------------------------
        |
        | Threshold for slow requests in milliseconds.
        |
        */
        'slow_request_threshold' => env('REQUEST_LOGGER_SLOW_THRESHOLD', 500),

        /*
        |--------------------------------------------------------------------------
        | Request Log Filename
        |--------------------------------------------------------------------------
        |
        | Filename for the request log.
        |
        */
        'request_log_filename' => env('REQUEST_LOGGER_FILENAME', 'request-monitor'),

        'viewer' => [
            /*
            |--------------------------------------------------------------------------
            | Request Log Viewer Headers
            |--------------------------------------------------------------------------
            |
            | Headers for the request log viewer.
            |
            */
            'headers' => ['Level', 'Method', 'URL', 'Response Size', 'Duration (ms)', 'RAM', 'Real RAM'],

            /*
            |--------------------------------------------------------------------------
            | Request Log Viewer Properties
            |--------------------------------------------------------------------------
            |
            | Properties to display in the request log viewer.
            |
            */
            'properties' => ['level', 'method', 'url', 'response_size', 'duration_ms', 'ram', 'real_ram'],
        ]
    ]
];
