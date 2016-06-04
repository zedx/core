<?php

namespace ZEDx\Console\Commands\Module;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use ZEDx\Console\Commands\Module\Traits\ModuleCommandTrait;
use ZEDx\Support\Stub;
use Modules;

class ModuleMakeProviderCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new service provider for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The service provider name.'],
            ['module', InputArgument::REQUIRED, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['master', null, InputOption::VALUE_NONE, 'Indicates the master service provider', null],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $stub = $this->option('master') ? 'scaffold/provider' : 'provider';

        $module = Modules::findOrFail($this->getModuleName());

        return (new Stub('/'.$stub.'.stub', [
            'NAMESPACE'        => $this->getClassNamespace($module),
            'CLASS'            => $this->getClass(),
            'LOWER_NAME'       => $module->getLowerName(),
            'MODULE'           => $this->getModuleName(),
            'NAME'             => $this->getFileName(),
            'STUDLY_NAME'      => $module->getStudlyName(),
            'MODULE_NAMESPACE' => Modules::config('namespace'),
            'PATH_VIEWS'       => config('modules.paths.generator.views'),
            'PATH_LANG'        => config('modules.paths.generator.lang'),
            'PATH_CONFIG'      => config('modules.paths.generator.config'),
        ]))->render();
    }

    /**
     * @return mixed
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
        return studly_case($this->argument('name'));
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return 'Providers';
    }
}
