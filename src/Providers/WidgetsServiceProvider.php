<?php

namespace ZEDx\Providers;

use Blade;
use File;
use Illuminate\Support\ServiceProvider;
use Widgets;
use ZEDx\Repositories\WidgetsRepository;

class WidgetsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerWidgets();
        $this->registerDirectives();
        $this->registerViews();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('widgets', function () {
            return new WidgetsRepository(config('widgets.paths.widgets'));
        });
    }

    /**
     * Register widget directives.
     *
     * @return void
     */
    protected function registerDirectives()
    {
        Blade::directive('widget', function ($widget) {
            $class = 'run'.$widget;

            return "<?php Widgets::$class; ?>";
        });

        Blade::directive('widgetSetting', function ($widget) {
            $class = 'setting'.$widget;

            return "<?php Widgets::$class; ?>";
        });
    }

    /**
     * Register views.
     *
     * @return void
     */
    protected function registerViews()
    {
        $paths = File::glob(base_path('widgets/*/*/*'));

        foreach ($paths as $path) {
            $viewsPath = $path.'/views';
            $namespace = str_replace(base_path('widgets').'/', '', $path);

            $widgetViewName = 'widget_'.strtolower(str_replace('/', '_', $namespace));

            $this->loadViewsFrom(array_merge(array_map(function ($path) use ($namespace) {
                return $path.'/widgets/'.$namespace;
            }, config('view.paths')), [$viewsPath]), $widgetViewName);
        }
    }

    /**
     * Register widgets.
     *
     * @return [type] [description]
     */
    protected function registerWidgets()
    {
        foreach (Widgets::frontend()->noFilter()->all() as $widget) {
            $widget->boot();
            $this->registerTranslationsFor($widget);
        }
    }

    /**
     * Register a widget translations.
     *
     * @param  Widget     $widget
     *
     * @return void
     */
    protected function registerTranslationsFor($widget)
    {
        $widgetLowerFullName = $widget->getLowerFullName();
        $widgetRootName = 'widget_'.str_replace('/', '_', $widgetLowerFullName);

        $langPath = base_path('resources/lang/widgets/'.$widgetLowerFullName);
        $rootLangPath = base_path('widgets/'.$widgetLowerFullName.'/lang');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $widgetRootName);
        } else {
            $this->loadTranslationsFrom($rootLangPath, $widgetRootName);
        }
    }
}
