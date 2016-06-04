<?php

namespace ZEDx\Console\Commands\Module;

use Illuminate\Console\Command;
use Modules;
use Symfony\Component\Console\Input\InputOption;

class ModuleListCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show list of all modules.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->table(['Name', 'Status', 'Path'], $this->getRows());
    }

    /**
     * Get table rows.
     *
     * @return array
     */
    public function getRows()
    {
        $rows = [];

        foreach ($this->getModules() as $module) {
            $rows[] = [
                $module->getStudlyName(),
                $module->enabled() ? 'Enabled' : 'Disabled',
                $module->getPath(),
            ];
        }

        return $rows;
    }

    public function getModules()
    {
        switch ($this->option('only')) {
            case 'enabled':
                return Modules::getByStatus(1);
                break;

            case 'disabled':
                return Modules::getByStatus(0);
                break;

            default:
                return Modules::all();
                break;
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['only', null, InputOption::VALUE_OPTIONAL, 'Types of modules will be displayed.', null],
        ];
    }
}
