<?php

namespace ZEDx\Console\Commands\Theme;

use Exception;
use File;
use Illuminate\Console\Command;
use Themes;
use ZEDx\Support\Stub;

class ThemeMakeCommand extends Command
{
    /**
     * Default Theme name.
     */
    protected $default = 'Default';

    /**
     * Theme name.
     */
    protected $name;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:make
                            {name : Theme name}
                            {--force : force creating theme}
                            {--switch : switch to created theme}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new theme.';

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

        if (studly_case($this->name) == $this->default) {
            throw new Exception("You can't create Default theme.");
        }

        $this->generate();

        if ($this->option('switch')) {
            $this->info("[ + ] Switching to {$this->name} theme.");
            Themes::frontend()->setActive(studly_case($this->name));
        }
    }

    protected function generate()
    {
        $themePath = $this->getThemePath();

        if (File::isDirectory($themePath)) {
            if ($this->option('force')) {
                File::deleteDirectory($themePath);
            } else {
                $this->error("Theme [{$this->name}] already exist!]");

                return;
            }
        }

        $this->cloneDefaultTheme()
            ->generateFiles()
            ->updateTaskThemeName();
    }

    /**
     * Get theme path.
     */
    protected function getThemePath($theme = null)
    {
        $name = $theme ?: $this->name;

        return base_path()
        .'/themes'
        .'/'.studly_case($name);
    }

    /**
     * Clone default theme.
     */
    protected function cloneDefaultTheme()
    {
        $newThemePath = $this->getThemePath();
        $defaultThemePath = $this->getThemePath($this->default);

        File::copyDirectory($defaultThemePath, $newThemePath);

        $this->info('[ + ] Default theme cloned');

        return $this;
    }

    /**
     * Get the list of files will created.
     *
     * @return array
     */
    public function getFiles()
    {
        return config('themes.stubs.files');
    }

    /**
     * Generate the files.
     */
    protected function generateFiles()
    {
        foreach ($this->getFiles() as $stub => $file) {
            $path = $this->getThemePath().'/'.$file;

            if (! File::isDirectory($dir = dirname($path))) {
                File::makeDirectory($dir, 0775, true);
            }

            File::put($path, $this->getStubContents($stub));

            $this->info("[ + ] Created : {$path}");
        }

        return $this;
    }

    /**
     * Update task (change default theme name).
     */
    protected function updateTaskThemeName()
    {
        $path = $this->getThemePath().'/task.js';
        $content = File::get($path);

        $search = 'themes/Default/assets/';
        $replace = 'themes/'.studly_case($this->name).'/assets/';

        $newContent = str_replace($search, $replace, $content);

        File::put($path, $newContent);

        $this->info('[ + ] Task updated');
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
        Stub::setBasePath(config('themes.stubs.path'));

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
        $replacements = config('themes.stubs.replacements');

        $namespace = config('themes.namespace');

        if (! isset($replacements[$stub])) {
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
