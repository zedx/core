<?php

namespace ZEDx\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;

class TemplateblockServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('block', function ($block) {
            $regex = preg_match('/\((["\'])(.*)\1\)/', $block, $out);
            if (count($out) == 3) {
                $block = $out[2];
            } else {
                return '';
            }

            return "<?php \Widgets::frontend()->renderBlock(\"{$block}\", get_defined_vars()); ?>";
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
