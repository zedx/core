<?php

namespace ZEDx\Console\Commands\Module;

use Modules;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use ZEDx\Console\Commands\Module\Traits\ModuleCommandTrait;
use ZEDx\Support\Stub;

class ModuleMakeConsoleCommand extends GeneratorCommand
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
    protected $name = 'module:make-console';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new Artisan console command for the specified module.';

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
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['command', null, InputOption::VALUE_OPTIONAL, 'The terminal command that should be assigned.', null],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = Modules::findOrFail($this->getModuleName());

        return (new Stub('/command.stub', [
            'COMMAND_NAME' => $this->getCommandName(),
            'NAMESPACE'    => $this->getClassNamespace($module),
            'CLASS'        => $this->getClass(),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = Modules::getModulePath($this->getModuleName());

        $seederPath = Modules::config('paths.generator.command');

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
     * @return string
     */
    private function getCommandName()
    {
        return $this->option('command') ?: 'command:name';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return 'Console';
    }
}
