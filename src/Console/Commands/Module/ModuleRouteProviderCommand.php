<?php

namespace ZEDx\Console\Commands\Module;

use Modules;
use Symfony\Component\Console\Input\InputArgument;
use ZEDx\Console\Commands\Module\Traits\ModuleCommandTrait;
use ZEDx\Support\Stub;

class ModuleRouteProviderCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'module:route-provider';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Generate a new route service provider for the specified module.';

    /**
     * The command arguments.
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
     * Get template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = Modules::findOrFail($this->getModuleName());

        return (new Stub('/route-provider.stub', [
            'NAMESPACE'         => $this->getClassNamespace($module),
            'CLASS'             => $this->getClass(),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'NAME'              => $this->getFileName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => Modules::config('namespace'),
        ]))->render();
    }

    /**
     * Get the destination file path.
     *
     * @return string
     */
    protected function getDestinationFilePath()
    {
        $path = Modules::getModulePath($this->getModuleName());

        $generatorPath = Modules::config('paths.generator.provider');

        return $path.$generatorPath.'/'.$this->getFileName().'.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return 'RouteServiceProvider';
    }
}
