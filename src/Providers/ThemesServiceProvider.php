<?php

namespace ZEDx\Providers;

use Illuminate\Support\ServiceProvider;
use ZEDx\Repositories\ThemesRepository;

class ThemesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerFrontendViews();
        $this->registerBackendViews();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('themes', function () {
            return new ThemesRepository();
        });
    }

    /**
     * Register views.
     *
     * @return void
     */
    protected function registerFrontendViews()
    {
        $viewsPath = base_path('themes/'.env('APP_FRONTEND_THEME').'/views');
        $this->registerViews($viewsPath, 'frontend');
    }

    /**
     * Register views.
     *
     * @return void
     */
    protected function registerBackendViews()
    {
        $this->registerViews([], 'backend');
    }

    /**
     * Register views.
     *
     * @return void
     */
    protected function registerViews($viewsPath, $type)
    {
        $this->loadViewsFrom(array_merge(array_map(function ($path) use ($type) {
            return $path.'/'.$type;
        }, config('view.paths')), [$viewsPath]), $type);
    }
}
