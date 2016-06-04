<?php

namespace ZEDx\Console\Commands\Module;

use Modules;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use ZEDx\Console\Commands\Module\Traits\ModuleCommandTrait;
use ZEDx\Support\Stub;

class ModuleMakeModelCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'model';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new model for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['model', InputArgument::REQUIRED, 'The name of model will be created.'],
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
            ['fillable', null, InputOption::VALUE_OPTIONAL, 'The fillable attributes.', null],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = Modules::findOrFail($this->getModuleName());

        return (new Stub('/model.stub', [
            'NAME'             => $this->getModelName(),
            'FILLABLE'         => $this->getFillable(),
            'NAMESPACE'        => $this->getClassNamespace($module),
            'CLASS'            => $this->getClass(),
            'LOWER_NAME'       => $module->getLowerName(),
            'MODULE'           => $this->getModuleName(),
            'STUDLY_NAME'      => $module->getStudlyName(),
            'MODULE_NAMESPACE' => Modules::config('namespace'),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = Modules::getModulePath($this->getModuleName());

        $seederPath = Modules::config('paths.generator.model');

        return $path.$seederPath.'/'.$this->getModelName().'.php';
    }

    /**
     * @return mixed|string
     */
    private function getModelName()
    {
        return studly_case($this->argument('model'));
    }

    /**
     * @return string
     */
    private function getFillable()
    {
        $fillable = $this->option('fillable');

        if (! is_null($fillable)) {
            $arrays = explode(',', $fillable);

            return json_encode($arrays);
        }

        return '[]';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return Modules::config('paths.generator.model');
    }
}
