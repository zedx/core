<?php

namespace ZEDx\Console\Commands\Module;

use Illuminate\Console\Command;
use Modules;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use ZEDx\Console\Commands\Module\Migrations\Migrator;
use ZEDx\Console\Commands\Module\Traits\MigrationLoaderTrait;

class ModuleMigrateRollbackCommand extends Command
{
    use MigrationLoaderTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:migrate-rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback the modules migrations.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $module = $this->argument('module');

        if (! empty($module)) {
            $this->rollback($module);

            return;
        }

        foreach (array_reverse(Modules::all()) as $module) {
            $this->line('Running for module: <info>'.$module->getName().'</info>');

            $this->rollback($module);
        }
    }

    /**
     * Rollback migration from the specified module.
     *
     * @param $module
     */
    public function rollback($module)
    {
        if (is_string($module)) {
            $module = Modules::findOrFail($module);
        }

        $migrator = new Migrator($module);

        $migrated = $migrator->rollback();

        if (count($migrated)) {
            foreach ($migrated as $migration) {
                $this->line("Rollback: <info>{$migration}</info>");
            }

            return;
        }

        $this->comment('Nothing to rollback.');
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
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],
        ];
    }
}
