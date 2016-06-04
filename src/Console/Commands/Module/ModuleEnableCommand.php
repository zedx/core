<?php

namespace ZEDx\Console\Commands\Module;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Modules;

class ModuleEnableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:enable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable the specified module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $module = Modules::findOrFail($this->argument('module'));

        if ($module->disabled()) {
            $module->enable();

            $this->info("Module [{$module}] enabled successful.");
        } else {
            $this->comment("Module [{$module}] has already enabled.");
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'Module name.'],
        ];
    }
}
