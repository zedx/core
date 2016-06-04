<?php

namespace ZEDx\Console\Commands\Widget;

use File;
use Illuminate\Console\Command;

class WidgetArchiveCommand extends Command
{
    /**
     * Archive file path.
     */
    protected $file;

    /**
     * Widget Author.
     */
    protected $author;

    /**
     * Widget name.
     */
    protected $name;

    /**
     * Widget type.
     */
    protected $type;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'widget:archive
                            {type : Widget type}
                            {author : Widget author}
                            {name : Widget name}
                            {--path= : path to save archive file }
                            {--push : push widget to ZEDx Api }
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create widget archive file.';

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

        $this->author = $this->argument('author');
        $this->name = $this->argument('name');
        $this->type = $this->argument('type');

        $this->file = $path.'/'.$this->getWidgetFileName().'-'.rand().'.zip';

        if (!$this->validWidgetType()) {
            $this->error('Invalid Widget Type, please choose between [Frontend, Backend]');

            return;
        }

        if (!File::isDirectory($this->getWidgetPath())) {
            $this->error("Widget [{$this->type}/{$this->author}/{$this->name}] doesn't exist!]");

            return;
        }

        $this->comment('[ + ] Creating widget archive ...');

        $this->makeArchive();

        if ($this->option('push')) {
            $this->pushToZEDxApi();
        }
    }

    protected function validWidgetType()
    {
        return in_array($this->type, ['Frontend', 'Backend']);
    }

    /**
     * Get widget file name.
     */
    protected function getWidgetFileName()
    {
        return studly_case($this->type)
        .studly_case($this->author)
        .studly_case($this->name);
    }

    /**
     * Get widget path.
     */
    protected function getWidgetPath()
    {
        return base_path()
        .'/widgets'
        .'/'.$this->type
        .'/'.studly_case($this->author)
        .'/'.studly_case($this->name);
    }

    protected function makeArchive()
    {
        $this->makeZipFile($this->file, $this->getWidgetPath());

        $this->info("[ + ] Widget archive saved in {$this->file}");
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
