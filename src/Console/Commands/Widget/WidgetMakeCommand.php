<?php

namespace ZEDx\Console\Commands\Widget;

use File;
use Illuminate\Console\Command;
use ZEDx\Support\Stub;

class WidgetMakeCommand extends Command
{
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
    protected $signature = 'widget:make
                            {author : Widget author}
                            {name : Widget name}
                            {--force : force creating widget}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new widget.';

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
        $this->author = $this->argument('author');
        $this->name = $this->argument('name');
        $this->type = $this->choice('Which widget type?', ['Frontend', 'Backend'], false);

        $this->generate();
    }

    protected function generate()
    {
        $widgetPath = $this->getWidgetPath();

        if (File::isDirectory($widgetPath)) {
            if ($this->option('force')) {
                File::deleteDirectory($widgetPath);
            } else {
                $this->error("Widget [{$this->author}/{$this->name} already exist!]");

                return;
            }
        }

        $this->generateFolders()
            ->generateFiles();
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

    /**
     * Get folders.
     */
    protected function getFolders()
    {
        return config('widgets.stubs.folders');
    }

    /**
     * Generate the folders.
     */
    protected function generateFolders()
    {
        foreach ($this->getFolders() as $folder) {
            $path = $this->getWidgetPath().'/'.$folder;

            File::makeDirectory($path, 0755, true);
        }

        return $this;
    }

    /**
     * Get the list of files will created.
     *
     * @return array
     */
    public function getFiles()
    {
        return config('widgets.stubs.files');
    }

    /**
     * Generate the files.
     */
    protected function generateFiles()
    {
        foreach ($this->getFiles() as $stub => $file) {
            $path = $this->getWidgetPath().'/'.$file;

            if (!File::isDirectory($dir = dirname($path))) {
                File::makeDirectory($dir, 0775, true);
            }

            File::put($path, $this->getStubContents($stub));

            $this->info("Created : {$path}");
        }
    }

    /**
     * Get the contents of the specified stub file by given stub name.
     *
     * @param $stub
     *
     * @return Stub
     */
    protected function getStubContents($stub)
    {
        Stub::setBasePath(config('widgets.stubs.path'));

        return (new Stub(
            '/'.$stub.'.stub',
            $this->getReplacement($stub))
        )->render();
    }

    /**
     * Get array replacement for the specified stub.
     *
     * @param $stub
     *
     * @return array
     */
    protected function getReplacement($stub)
    {
        $replacements = config('widgets.stubs.replacements');

        $namespace = config('widgets.namespace');

        if (!isset($replacements[$stub])) {
            return [];
        }

        $keys = $replacements[$stub];

        $replaces = [];

        foreach ($keys as $key) {
            $exp = explode('_', $key);

            if (method_exists($this, $method = 'get'.ucfirst(strtolower($exp[0])).'Replacement')) {
                $replaces[$key] = call_user_func([$this, $method], $this->{strtolower($exp[1])});
            } else {
                $replaces[$key] = null;
            }
        }

        return $replaces;
    }

    /**
     * Get a value in lower case.
     *
     * @return string
     */
    protected function getLowerReplacement($value)
    {
        return strtolower($value);
    }

    /**
     * Get a value in studly case.
     *
     * @return string
     */
    protected function getStudlyReplacement($value)
    {
        return studly_case($value);
    }
}
