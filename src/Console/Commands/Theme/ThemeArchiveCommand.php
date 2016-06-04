<?php

namespace ZEDx\Console\Commands\Theme;

use File;
use Illuminate\Console\Command;
use Zipper;

class ThemeArchiveCommand extends Command
{
    /**
     * Archive file path.
     */
    protected $file;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:archive
                            {--path= : path to save archive file }
                            {--push : push theme to ZEDx Api }
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create theme archive file.';

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
        $this->file = $path.'/'.env('APP_FRONTEND_THEME').'-'.rand().'.zip';

        $this->comment('[ + ] Creating theme archive ...');
        $this->makeArchive();

        if ($this->option('push')) {
            $this->pushToZEDxApi();
        }
    }

    /**
     * Get theme path.
     */
    protected function getThemePath()
    {
        return base_path()
        .'/themes'
        .'/'.env('APP_FRONTEND_THEME');
    }

    protected function makeArchive()
    {
        $assets = $this->getThemePath().'/assets';
        $lang = $this->getThemePath().'/lang';
        $public = public_path().'/build/frontend';
        $views = $this->getThemePath().'/views';
        $task = $this->getThemePath().'/task.js';
        $zedx = $this->getThemePath().'/zedx.json';

        Zipper::make($this->file)
            ->add($zedx)
            ->add($task)
            ->addString('rev-manifest.json', $this->getManifestContent())
            ->folder('assets')->add($assets)
            ->folder('lang')->add($lang)
            ->folder('public')->add($public)
            ->folder('views')->add($views)
            ->close();

        $this->info("[ + ] Theme archive saved in {$this->file}");
    }

    protected function getManifestContent()
    {
        $manifestPath = public_path().'/build/rev-manifest.json';

        $originalManifestContent = json_decode(File::get($manifestPath), true);

        $manifestContent['frontend/css/styles.css'] = $originalManifestContent['frontend/css/styles.css'];
        $manifestContent['frontend/js/scripts.js'] = $originalManifestContent['frontend/js/scripts.js'];

        $manifest = json_encode($manifestContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return preg_replace('/^(  +?)\\1(?=[^ ])/m', '$1', $manifest);
    }

    protected function pushToZEDxApi()
    {
    }
}
