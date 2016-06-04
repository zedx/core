<?php

namespace ZEDx\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setCarbonLocale();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('TemplateSkeleton', function () {
            return new \ZEDx\Utils\TemplateSkeleton();
        });

        $this->app->bind('Payment', function () {
            return new \ZEDx\Utils\Payment();
        });

        $this->app->bind('Updater', function () {
            return new \ZEDx\Utils\Updater();
        });

        View::addNamespace('__templates', storage_path('app/views'));
    }

    protected function setCarbonLocale()
    {
        $locale = env('APP_LOCALE', 'en');
        if (file_exists(base_path("vendor/nesbot/carbon/src/Carbon/Lang/$locale.php"))) {
            Carbon::setLocale($locale);
        } else {
            Carbon::setLocale('en');
        }
    }
}
