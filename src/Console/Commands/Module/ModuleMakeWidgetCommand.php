<?php

namespace ZEDx\Console\Commands\Module;

use Illuminate\Console\Command;
use ZEDx\Console\Commands\Module\Traits\ModuleCommandTrait;

class ModuleMakeWidgetCommand extends Command
{
    use ModuleCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-widget {module} {name} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new widget for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The name of module that will be used.'],
            ['name', InputArgument::REQUIRED, 'The name of the widget that will be created.'],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name   = $this->argument('name');
        $module = $this->getModuleName();
        $force  = $this->option('force');

        // Publishing empty widgets (creates symlinks)
        $this->call('module:publish-widget', [
            'module'  => $module,
            '--force' => $force,
        ]);

        // Creating a widget as usual
        $this->call('widget:make', [
            'author'  => $module,
            'name'    => $name,
            '--force' => $force,
        ]);
    }
}
