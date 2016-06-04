<?php

namespace ZEDx\Providers;

use Illuminate\Support\ServiceProvider;
use ZEDx\Repositories\MapsRepository;

class MapsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('maps', function () {
            return new MapsRepository(config('maps.path'));
        });
    }
}
