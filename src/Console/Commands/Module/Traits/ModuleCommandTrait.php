<?php

namespace ZEDx\Console\Commands\Module\Traits;

use Modules;

trait ModuleCommandTrait
{
    /**
     * Get the module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        $module = Modules::findOrFail($this->argument('module'));

        return $module->getStudlyName();
    }
}
