<?php

namespace ZEDx\Console\Commands\Module;

use File;
use Illuminate\Console\Command;
use Modules;

class ModulePublishCommand extends Command
{
    /**
     * Module name.
     */
    protected $name;

    /**
     * Module instance.
     */
    protected $module;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:publish
                            {name? : Module name}
                            {--force : force publishing module assets}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish module assets.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->name = $this->argument('name');

        if (!$this->name) {
            $this->publishAll();

            return;
        }

        $this->publish();
    }

    protected function publishAll()
    {
        foreach (Modules::enabled() as $module) {
            $this->call('module:publish', [
                'name'    => $module->name,
                '--force' => $this->option('force'),
            ]);
        }
    }

    /**
     * Get destination path.
     *
     * @return string
     */
    public function getDestinationPath()
    {
        return Modules::assetPath($this->module->getLowerName());
    }

    /**
     * Get source path.
     *
     * @return string
     */
    public function getSourcePath()
    {
        return $this->module->getExtraPath(
            Modules::config('paths.generator.distAssets')
        );
    }

    protected function publish()
    {
        $this->module = Modules::findOrFail($this->name);

        $this->output->writeln('');
        $this->table([], [["Publishing Assets of Module <info>{$this->module->getStudlyName()}</info>"]]);
        $this->output->writeln('');

        $sourcePath = $this->getSourcePath();
        $destinationPath = $this->getDestinationPath();

        if (!File::isDirectory($sourcePath)) {
            $this->info("[ ~ ] Nothing to publish for Module [{$this->module->getStudlyName()}]");

            return;
        }

        if (File::isDirectory($destinationPath)) {
            if ($this->option('force')) {
                File::deleteDirectory($destinationPath);
            } else {
                $this->error("Assets of Module [{$this->module->getStudlyName()}] already exist!");

                return;
            }
        }

        $this->info("[ + ] Creating $destinationPath");
        File::makeDirectory($destinationPath, 0755, true);

        $this->info("[ + ] Publishing assets from {$sourcePath}");
        File::copyDirectory($sourcePath, $destinationPath);
        $this->info('Assets published');
    }
}
