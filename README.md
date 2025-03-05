# Request Query Monitor

Request Query Monitor for Laravel

**RequestQueryMonitor for Laravel** is a lightweight package designed to monitor and log **API requests** and **database queries** in your Laravel applications. It helps you identify slow endpoints and database queries, providing better visibility into your application's performance.

## Installation

Install via Composer:

```bash
composer require mpernia/request-query-monitor
```

## Configuration

Publish the configuration files:

```bash
php artisan vendor:publish --tag=monitoring-config
```

This will publish the following files into your `config` folder: `monitoring.php`

**Note:** add this flag `--force` to overwrite any existing files.

## Environment variables

You can also set these values in your `.env` file:

```dotenv
QUERY_LOGGER_ENABLED=false                 # Enabled the queries logged
QUERY_LOGGER_ENABLED_MEMORY_USAGE=false    # Enabled return the memory usage 
QUERY_LOGGER_SLOW_THRESHOLD=100            # Slow query threshold in milliseconds
QUERY_LOGGER_FILENAME=query                # The name of the log file 
REQUEST_LOGGER_ENABLED=false               # Enabled the requests logged
REQUEST_LOGGER_ENABLED_MEMORY_USAGE=false  # Enabled return the memory usage
REQUEST_LOGGER_SLOW_THRESHOLD=500          # Slow request threshold in milliseconds
REQUEST_LOGGER_FILENAME=request            # The name of the log file 
```

## Usage

### Database Query Logging

When `QUERY_LOGGER_ENABLED` is set to `true`, all executed database queries are logged, with warnings for queries exceeding the configured threshold.

### API Request Logging

#### Registering the middleware `ApiRequestLogger` in

##### Laravel version 11 or higher:

Edit the `bootstrap/app.php` file and put this code:

```php
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('web', [
            \RequestQueryMonitor\Http\Middleware\ApiRequestLogger::class,
        ]);
        $middleware->group('api', [
            \RequestQueryMonitor\Http\Middleware\ApiRequestLogger::class,
        ]);
    })
```

##### laravel version 10 or lower:

Edit the `app/Http/Kernel.php` file and put this code:

```php
    protected $middlewareGroups = [
        'web' => [
            \RequestQueryMonitor\Http\Middleware\ApiRequestLogger::class,
        ],
        'api' => [
            \RequestQueryMonitor\Http\Middleware\ApiRequestLogger::class,
        ],
    ];
```

All incoming API requests will be logged, including:
* HTTP method
* Full URL
* Response status code
* Execution time (in milliseconds)
* IP address
* User agent
* Incoming payload (with sensitive data like passwords automatically excluded)

## Examples

### Request Log Output

The HTTP request information is logged:

```json
{
    "method": "POST",
    "url": "https://example.com/api/v1/orders",
    "status": 201,
    "duration_ms": 245.87,
    "ip": "123.45.67.89",
    "user_agent": "PostmanRuntime/7.36.0",
    "payload": {
        "product_id": 123,
        "quantity": 2
    }
}
```

### Query Log Output

Database queries will log like this:

```json
{
    "query": "select * from users where email = 'john@example.com'",
    "time_ms": 5.24
}
```

### View Log with Artisan Command

#### Show Requests Log on console

```bash
php artisan show:log --requests --file=request-monitor. log
```

#### Output

| Level | Method | URL | Response Size | Duration (ms) | RAM | Real RAM |
| --- | --- | --- | --- | --- | --- | --- |
| WARNING | GET | https://example.com/api/v1/orders | 206.06 KB | 937.74 | 3.03 MB | 12.00 MB |
| INFO | POST | https://example.com/api/v1/orders | 1.37 KB | 281.13 | 2.75 MB | 12.00 MB |

#### Show Queries Log on console

```bash
php artisan show:log --queries --file=query-monitor.log
```

#### Output

| Level | Class  | Method | Duration (ms) | RAM | Real RAM |
| --- | --- | --- | --- | --- | --- |
| WARNING | App\Repositories\Order\OrderRepository | search | 170.83 | 2.62 MB | 12.00 MB |
| INFO    | App\Repositories\Order\OrderRepository | create | 1.58 | 2.63 MB | 12.00 MB |

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.
