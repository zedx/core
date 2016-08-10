<?php

namespace ZEDx\Console\Commands\Module;

use Illuminate\Console\Command;
use Modules;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ModuleMigrateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the migrations from the specified module or from all modules.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $name = $this->argument('module');

        if ($name) {
            return $this->migrate($name);
        }

        foreach (Modules::enabled() as $module) {
            $this->line('Running for module: <info>'.$module->getName().'</info>');

            $this->migrate($module);
        }
    }

    /**
     * Run the migration from the specified module.
     *
     * @param string $name
     *
     * @return mixed
     */
    protected function migrate($name)
    {
        $module = Modules::findOrFail($name);

        $this->call('migrate', [
            '--path'     => $this->getPath($module),
            '--database' => $this->option('database'),
            '--pretend'  => $this->option('pretend'),
            '--force'    => $this->option('force'),
        ]);

        if ($this->option('seed')) {
            $this->call('module:seed', ['module' => $name]);
        }
    }

    /**
     * Get migration path for specific module.
     *
     * @param Module $module
     *
     * @return string
     */
    protected function getPath($module)
    {
        $path = $module->getExtraPath(config('modules.paths.generator.migration'));

        return str_replace(base_path(), '', $path);
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
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.'],
        ];
    }
}
