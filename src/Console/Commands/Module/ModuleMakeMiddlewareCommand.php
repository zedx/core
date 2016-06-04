<?php

namespace ZEDx\Console\Commands\Module;

use Modules;
use Symfony\Component\Console\Input\InputArgument;
use ZEDx\Console\Commands\Module\Traits\ModuleCommandTrait;
use ZEDx\Support\Stub;

class ModuleMakeMiddlewareCommand extends GeneratorCommand
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
    protected $name = 'module:make-middleware';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new middleware class for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the command.'],
            ['module', InputArgument::REQUIRED, 'The name of module will be used.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = Modules::findOrFail($this->getModuleName());

        return (new Stub('/filter.stub', [
            'NAMESPACE'        => $this->getClassNamespace($module),
            'CLASS'            => $this->getClass(),
            'LOWER_NAME'       => $module->getLowerName(),
            'MODULE'           => $this->getModuleName(),
            'NAME'             => $this->getFileName(),
            'STUDLY_NAME'      => $this->getFileName(),
            'MODULE_NAMESPACE' => Modules::config('namespace'),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = Modules::getModulePath($this->getModuleName());

        $seederPath = Modules::config('paths.generator.filter');

        return $path.$seederPath.'/'.$this->getFileName().'.php';
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
        return 'Http\Middleware';
    }
}
