<?php

namespace ZEDx\Console\Commands;

use File;
use Illuminate\Console\Command;

class BackendPublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backend:publish
                            {--force : force publishing backend assets}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish backend assets.';

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
        $this->publish();
    }

    protected function publish()
    {
        $themePath = $this->getThemePath();
        $themePublicPath = $this->getThemePublicPath();

        if (!File::isDirectory($themePath)) {
            $this->error("Theme Backend doesn't exist!]");

            return;
        }

        if (File::isDirectory($themePublicPath)) {
            if ($this->option('force')) {
                File::deleteDirectory($themePublicPath);
            } else {
                $this->error('Assets already exist!]');

                return;
            }
        }

        $this->comment("[ + ] Creating $themePublicPath");
        File::makeDirectory($themePublicPath, 0755, true);

        $this->comment("[ + ] Publishing assets from $themePath/assets/dist");
        File::copyDirectory($themePath.'/assets/dist', $themePublicPath);

        $this->comment('[ + ] Merging public Manifest file');
        $this->mergePublicManifest();

        $this->info('Assets published');
    }

    /**
     * Get theme path.
     */
    protected function getThemePath()
    {
        return core_path()
            .'/resources';
    }

    /**
     * Get theme public assets path.
     */
    protected function getThemePublicPath()
    {
        return public_path()
            .'/build/backend';
    }

    /**
     * Merge public manifest file.
     *
     * @param string $theme
     *
     * @return bool
     */
    protected function mergePublicManifest()
    {
        $themeManifestPath = $this->getThemePath().'/assets/rev-manifest.json';
        $originalManifestPath = public_path('build/rev-manifest.json');

        if (File::exists($originalManifestPath)) {
            $originalManifest = json_decode(File::get($originalManifestPath), true);
        } else {
            $originalManifest = [];
        }

        $themeManifest = json_decode(File::get($themeManifestPath), true);

        $manifest = array_merge($originalManifest, $themeManifest);
        $manifestContent = json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $manifestContent = preg_replace('/^(  +?)\\1(?=[^ ])/m', '$1', $manifestContent);

        File::put($originalManifestPath, $manifestContent);
    }
}
