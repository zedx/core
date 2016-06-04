<?php

namespace ZEDx\Console\Commands\Module;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use ZEDx\Console\Commands\Module\Traits\ModuleCommandTrait;

class ModuleMigrateRefreshCommand extends Command
{
    use ModuleCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:migrate-refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback & re-migrate the modules migrations.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->call('module:migrate-reset', [
            'module'     => $this->getModuleName(),
            '--database' => $this->option('database'),
            '--force'    => $this->option('force'),
        ]);

        $this->call('module:migrate', [
            'module'     => $this->getModuleName(),
            '--database' => $this->option('database'),
            '--force'    => $this->option('force'),
        ]);

        if ($this->option('seed')) {
            $this->call('module:seed', [
                'module' => $this->getModuleName(),
            ]);
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
            ['module', InputArgument::REQUIRED, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.'],
        ];
    }
}
