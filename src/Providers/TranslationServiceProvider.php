<?php

namespace ZEDx\Providers;

use Illuminate\Translation\TranslationServiceProvider as BaseTranslationServiceProvider;
use ZEDx\Utils\Translation\FileLoader;

class TranslationServiceProvider extends BaseTranslationServiceProvider
{
    /**
     * Register the translation line loader.
     *
     * @return void
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            $paths = [$app['path.lang'], base_path('resources/lang/core')];

            return new FileLoader($app['files'], $paths);
        });
    }
}
