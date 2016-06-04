<?php

namespace ZEDx\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ExpireAdCommand::class,
        Commands\BackendPublishCommand::class,

        Commands\Zedx\ZedxResetCommand::class,
        Commands\Zedx\ZedxUpdateCommand::class,
        Commands\Zedx\ZedxInstallCommand::class,

        /* Module Commands */
        Commands\Module\ModuleArchiveCommand::class,
        Commands\Module\ModuleMakeCommand::class,
        Commands\Module\ModuleMakeControllerCommand::class,
        Commands\Module\ModuleMakeProviderCommand::class,
        Commands\Module\ModuleMakeSeedCommand::class,
        Commands\Module\ModuleMakeRequestCommand::class,
        Commands\Module\ModuleSeedCommand::class,
        Commands\Module\ModuleMigrateCommand::class,
        Commands\Module\ModuleMigrateResetCommand::class,
        Commands\Module\ModuleMigrateRollbackCommand::class,
        Commands\Module\ModuleMigrateRefreshCommand::class,
        Commands\Module\ModuleListCommand::class,
        Commands\Module\ModuleDisableCommand::class,
        Commands\Module\ModuleEnableCommand::class,
        Commands\Module\ModuleMakeMiddlewareCommand::class,
        Commands\Module\ModuleRouteProviderCommand::class,
        Commands\Module\ModuleMakeModelCommand::class,
        Commands\Module\ModuleMakeConsoleCommand::class,
        Commands\Module\ModulePublishCommand::class,

        /* Widget Commands */
        Commands\Widget\WidgetMakeCommand::class,
        Commands\Widget\WidgetPublishCommand::class,
        Commands\Widget\WidgetArchiveCommand::class,

        /* Theme Commands */
        Commands\Theme\ThemePublishCommand::class,
        Commands\Theme\ThemeMakeCommand::class,
        Commands\Theme\ThemeSetCommand::class,
        Commands\Theme\ThemeArchiveCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('ad:expire')
            ->daily();

        //@todo : for demo
        //$this->refreshZedx($schedule);
    }

    protected function refreshZedx(Schedule $schedule)
    {
        $slug = str_slug(Carbon::now());

        $filePathMigration = storage_path('logs/refresh_'.$slug.'/migration_.txt');
        $filePathGit = storage_path('logs/refresh_'.$slug.'/git.txt');

        $schedule->exec('mkdir '.storage_path('logs/refresh_'.$slug))
            ->hourly();

        $schedule->command('down')
            ->hourly()
            ->withoutOverlapping()
            ->sendOutputTo($filePathMigration);

        $schedule->command('migrate:refresh --seed')
            ->hourly()
            ->withoutOverlapping()
            ->appendOutputTo($filePathMigration);

        $schedule->exec('cd '.base_path().' && git stash')
            ->hourly()
            ->sendOutputTo($filePathGit);

        $schedule->command('up')
            ->hourly()
            ->withoutOverlapping()
            ->appendOutputTo($filePathMigration);
    }
}
