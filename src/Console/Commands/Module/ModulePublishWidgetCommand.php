<?php

namespace ZEDx\Console\Commands\Module;

use File;
use Illuminate\Console\Command;
use ZEDx\Console\Commands\Module\Traits\ModuleCommandTrait;

class ModulePublishWidgetCommand extends Command
{
    use ModuleCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:publish-widget {module} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish widget for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The name of module that will be used.'],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->linkWidget();
    }

    public function linkWidget()
    {
        $module = $this->getModuleName();
        $force = $this->option('force');

        // Checking symbolic links in back/front-end
        foreach (['Backend', 'Frontend'] as $type) {
            $link = config('widgets.path')."/$type/$module";
            $target = base_path('modules')."/$module/Widgets/$type";

            if (File::exists($target) && !File::exists($link)) {
                symlink($target, $link);
            }
        }

        $this->info("Module [ $module ] widgets are linked");
    }
}
