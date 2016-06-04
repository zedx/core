<?php

namespace ZEDx\Console\Commands\Module;

use File;
use Illuminate\Console\Command;

class ModuleArchiveCommand extends Command
{
    /**
     * Archive file path.
     */
    protected $file;

    /**
     * Module name.
     */
    protected $name;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:archive
                            {name : Module name}
                            {--path= : path to save archive file }
                            {--push : push module to ZEDx Api }
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create module archive file.';

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
        $path = $this->option('path') ?: base_path();

        $this->name = studly_case($this->argument('name'));

        $this->file = $path.'/'.$this->name.'-'.rand().'.zip';

        if (!File::isDirectory($this->getModulePath())) {
            $this->error("Module [{$this->name}] doesn't exist!]");

            return;
        }

        $this->comment('[ + ] Creating module archive ...');

        $this->makeArchive();

        if ($this->option('push')) {
            $this->pushToZEDxApi();
        }
    }

    /**
     * Get module path.
     */
    protected function getModulePath()
    {
        return base_path()
            .'/modules'
            .'/'.$this->name;
    }

    protected function makeArchive()
    {
        $this->makeZipFile($this->file, $this->getModulePath());

        $this->info("[ + ] Module archive saved in {$this->file}");
    }

    protected function makeZipFile($filePath, $rootPath)
    {
        // Get real path for our folder
        $rootPath = realpath($rootPath);

        // Initialize archive object
        $zip = new \ZipArchive();
        $zip->open($filePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rootPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
    }

    protected function pushToZEDxApi()
    {
    }
}
