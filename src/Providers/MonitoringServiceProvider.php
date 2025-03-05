<?php

namespace RequestQueryMonitor\Providers;

use Illuminate\Support\ServiceProvider;
use RequestQueryMonitor\Commands\ShowLogCommand;
use RequestQueryMonitor\Http\Middleware\ApiRequestLogger;
use RequestQueryMonitor\Services\{Logger\QueryLogger, Logger\RequestLogger};

class MonitoringServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(QueryLogger::class, function ($app) {
            return new QueryLogger();
        });

        $this->app->singleton(RequestLogger::class, function ($app) {
            return new RequestLogger();
        });
    }

    public function boot(): void
    {
        $configPath = __DIR__ . '/../config/monitoring.php';
        $this->mergeConfigFrom($configPath, 'monitoring');
        if (config('monitoring.queries.enabled', false)) {
            $this->app->make(QueryLogger::class)->register();
        }
        if (config('monitoring.requests.enabled', false)) {
            $this->app['router']->pushMiddlewareToGroup('api', ApiRequestLogger::class);
        }
        $this->publishes([$configPath => config_path('monitoring.php')], 'monitoring-config');
        $this->commands([
            ShowLogCommand::class
        ]);
    }
}
