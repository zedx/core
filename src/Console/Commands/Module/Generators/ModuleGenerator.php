<?php

namespace ZEDx\Console\Commands\Module\Generators;

use File;
use Illuminate\Config\Repository as Config;
use Illuminate\Console\Command as Console;
use Modules;
use ZEDx\Support\Stub;

class ModuleGenerator extends Generator
{
    /**
     * The module name will created.
     *
     * @var string
     */
    protected $name;

    /**
     * The laravel console instance.
     *
     * @var Console
     */
    protected $console;

    /**
     * Force status.
     *
     * @var bool
     */
    protected $force = false;

    /**
     * Generate a plain module.
     *
     * @var bool
     */
    protected $plain = false;

    /**
     * The constructor.
     *
     * @param $name
     * @param Config  $config
     * @param Console $console
     */
    public function __construct($name, Console $console = null)
    {
        $this->name = $name;
        $this->console = $console;
    }

    /**
     * Set plain flag.
     *
     * @param bool $plain
     *
     * @return $this
     */
    public function setPlain($plain)
    {
        $this->plain = $plain;

        return $this;
    }

    /**
     * Get the name of module will created. By default in studly case.
     *
     * @return string
     */
    public function getName()
    {
        return studly_case($this->name);
    }

    /**
     * Get the laravel console instance.
     *
     * @return Console
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * Set the laravel console instance.
     *
     * @param Console $console
     *
     * @return $this
     */
    public function setConsole($console)
    {
        $this->console = $console;

        return $this;
    }

    /**
     * Get the list of folders will created.
     *
     * @return array
     */
    public function getFolders()
    {
        return array_values(Modules::config('paths.generator'));
    }

    /**
     * Get the list of files will created.
     *
     * @return array
     */
    public function getFiles()
    {
        return Modules::config('stubs.files');
    }

    /**
     * Set force status.
     *
     * @param bool|int $force
     *
     * @return $this
     */
    public function setForce($force)
    {
        $this->force = $force;

        return $this;
    }

    /**
     * Generate the module.
     */
    public function generate()
    {
        $name = $this->getName();

        if (Modules::has($name)) {
            if ($this->force) {
                Modules::delete($name);
            } else {
                $this->console->error("Module [{$name}] already exist!");

                return;
            }
        }

        $this->generateFolders();

        $this->generateFiles();

        if (!$this->plain) {
            $this->generateResources();
        }

        $this->console->info("Module [{$name}] created successfully.");
    }

    /**
     * Generate the folders.
     */
    public function generateFolders()
    {
        foreach ($this->getFolders() as $folder) {
            $path = Modules::getModulePath($this->getName()).'/'.$folder;

            File::makeDirectory($path, 0755, true);

            $this->generateGitKeep($path);
        }
    }

    /**
     * Generate git keep to the specified path.
     *
     * @param string $path
     */
    public function generateGitKeep($path)
    {
        File::put($path.'/.gitkeep', '');
    }

    /**
     * Generate the files.
     */
    public function generateFiles()
    {
        foreach ($this->getFiles() as $stub => $file) {
            $path = Modules::getModulePath($this->getName()).$file;

            if (!File::isDirectory($dir = dirname($path))) {
                File::makeDirectory($dir, 0775, true);
            }

            File::put($path, $this->getStubContents($stub));

            $this->console->info("Created : {$path}");
        }
    }

    /**
     * Generate some resources.
     */
    public function generateResources()
    {
        $this->console->call('module:make-seed', [
            'name'     => $this->getName(),
            'module'   => $this->getName(),
            '--master' => true,
        ]);

        $this->console->call('module:make-provider', [
            'name'     => $this->getName().'ServiceProvider',
            'module'   => $this->getName(),
            '--master' => true,
        ]);

        $this->console->call('module:make-controller', [
            'controller' => $this->getName().'Controller',
            'module'     => $this->getName(),
        ]);

        $this->console->call('module:make-backend-controller', [
            'controller' => $this->getName().'Controller',
            'module'     => $this->getName(),
        ]);
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
        return (new Stub(
            '/'.$stub.'.stub',
            $this->getReplacement($stub))
        )->render();
    }

    /**
     * get the list for the replacements.
     */
    public function getReplacements()
    {
        return Modules::config('stubs.replacements');
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
        $replacements = Modules::config('stubs.replacements');

        $namespace = Modules::config('namespace');

        if (!isset($replacements[$stub])) {
            return [];
        }

        $keys = $replacements[$stub];

        $replaces = [];

        foreach ($keys as $key) {
            if (method_exists($this, $method = 'get'.ucfirst(studly_case(strtolower($key))).'Replacement')) {
                $replaces[$key] = call_user_func([$this, $method]);
            } else {
                $replaces[$key] = null;
            }
        }

        return $replaces;
    }

    /**
     * Get the module name in lower case.
     *
     * @return string
     */
    protected function getLowerNameReplacement()
    {
        return strtolower($this->getName());
    }

    /**
     * Get the module name in studly case.
     *
     * @return string
     */
    protected function getStudlyNameReplacement()
    {
        return $this->getName();
    }

    /**
     * Get replacement for $VENDOR$.
     *
     * @return string
     */
    protected function getVendorReplacement()
    {
        return Modules::config('composer.vendor');
    }

    /**
     * Get replacement for $MODULE_NAMESPACE$.
     *
     * @return string
     */
    protected function getModuleNamespaceReplacement()
    {
        return str_replace('\\', '\\\\', Modules::config('namespace'));
    }

    /**
     * Get replacement for $AUTHOR_NAME$.
     *
     * @return string
     */
    protected function getAuthorNameReplacement()
    {
        return Modules::config('composer.author.name');
    }

    /**
     * Get replacement for $AUTHOR_EMAIL$.
     *
     * @return string
     */
    protected function getAuthorEmailReplacement()
    {
        return Modules::config('composer.author.email');
    }
}
