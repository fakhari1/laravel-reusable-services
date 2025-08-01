<?php

namespace Modules\Modules\Shared\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Modules\Shared\Services\HttpRequestHandler;

class SharedServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(HttpRequestHandler::class, function ($app) {
            return new HttpRequestHandler();
        });
    }

    public function boot()
    {
    }

}
