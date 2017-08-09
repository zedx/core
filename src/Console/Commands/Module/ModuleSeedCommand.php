<?php

namespace ZEDx\Console\Commands\Module;

use Illuminate\Console\Command;
use Modules;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use ZEDx\Console\Commands\Module\Traits\ModuleCommandTrait;

class ModuleSeedCommand extends Command
{
    use ModuleCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run database seeder from the specified module or from all modules.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $name = $this->argument('module');

        if ($name) {
            if (!Modules::has(studly_case($name))) {
                return $this->error("Module [$name] does not exists.");
            }

            $class = $this->getSeederName($name);
            if (class_exists($class)) {
                $this->dbseed($name);

                return $this->info("Module [$name] seeded.");
            } else {
                return $this->error("Class [$class] does not exists.");
            }
        }

        foreach (Modules::enabled() as $module) {
            $name = $module->getName();

            if (class_exists($this->getSeederName($name))) {
                $this->dbseed($name);

                $this->info("Module [$name] seeded.");
            }
        }

        return $this->info('All modules seeded.');
    }

    /**
     * Seed the specified module.
     *
     * @parama string  $name
     *
     * @return array
     */
    protected function dbseed($name)
    {
        $params = [
            '--class' => $this->option('class') ?: $this->getSeederName($name),
        ];

        if ($option = $this->option('database')) {
            $params['--database'] = $option;
        }

        if ($this->option('force')) {
            $params['--force'] = true;
        }

        $this->call('db:seed', $params);
    }

    /**
     * Get master database seeder name for the specified module.
     *
     * @param string $name
     *
     * @return string
     */
    public function getSeederName($name)
    {
        $name = studly_case($name);

        $namespace = Modules::config('namespace');

        return $namespace.'\\'.$name.'\Database\Seeders\\'.$name.'DatabaseSeeder';
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
            ['class', null, InputOption::VALUE_OPTIONAL, 'The class name of the root seeder', null],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['all', null, InputOption::VALUE_NONE, 'Whether or not we should seed all modules.'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to seed.'],
        ];
    }
}
