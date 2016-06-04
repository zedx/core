<?php

namespace ZEDx\Providers;

use Illuminate\Support\ServiceProvider;
use Modules;
use ZEDx\Repositories\ModulesRepository;
use ZEDx\Support\Stub;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Modules.
     *
     * @var Modules
     */
    protected $modules;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Modules::bootAll();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        Stub::setBasePath(config('modules.stubs.path'));

        $this->app->singleton('modules', function () {
            $modules = new ModulesRepository(config('modules.paths.modules'));
            $modules->registerAll();

            return $modules;
        });
    }
}
