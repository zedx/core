<?php

namespace ZEDx\Console\Commands\Module\Traits;

use File;
use Modules;

trait MigrationLoaderTrait
{
    /**
     * Include all migrations files from the specified module.
     *
     * @param string $module
     */
    protected function loadMigrationFiles($module)
    {
        $path = Modules::getModulePath($module).$this->getMigrationGeneratorPath();

        $files = File::glob($path.'/*_*.php');

        foreach ($files as $file) {
            File::requireOnce($file);
        }
    }

    /**
     * Get migration generator path.
     *
     * @return string
     */
    protected function getMigrationGeneratorPath()
    {
        return Modules::config('paths.generator.migration');
    }
}
