<?php

namespace Sergeich5\LaravelIpMiddleware;

use Illuminate\Support\ServiceProvider;

class IpMiddlewareServiceProvider extends ServiceProvider
{
    function boot()
    {
        if ($this->app->runningInConsole())
            $this->publishes([
                __DIR__ . '/../config/ip-middleware.php' => config_path('ip-middleware.php'),
            ], 'ip-middleware');
    }
}
