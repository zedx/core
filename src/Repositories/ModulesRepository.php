<?php

namespace ZEDx\Repositories;

use Countable;
use File;
use ZEDx\Support\Json;
use ZEDx\Contracts\ComponentInterface;
use ZEDx\Exceptions\ModuleNotFoundException;
use ZEDx\Components\Module;

class ModulesRepository implements ComponentInterface, Countable
{
    /**
     * The module path.
     *
     * @var string|null
     */
    protected $path;

    /**
     * @var string
     */
    protected $stubPath;

    /**
     * The constructor.
     *
     * @param string|null $path
     */
    public function __construct($path = null)
    {
        $this->path = $path;
    }

    /**
     * Get scanned modules path.
     *
     * @return array
     */
    public function getScanPath()
    {
        return $this->getPath().'/*';
    }

    /**
     * Get & scan all modules.
     *
     * @return array
     */
    public function scan()
    {
        $path = $this->getScanPath();

        $modules = [];

        $manifests = File::glob("{$path}/zedx.json");

        is_array($manifests) || $manifests = [];

        foreach ($manifests as $manifest) {
            $name = Json::make($manifest)->get('name');

            $lowerName = strtolower($name);

            $modules[$name] = new Module($lowerName, dirname($manifest));
        }

        return $modules;
    }

    /**
     * Get all modules.
     *
     * @return array
     */
    public function all()
    {
        return $this->scan();
    }

    /**
     * Get all modules as collection instance.
     *
     * @return Collection
     */
    public function toCollection()
    {
        return collect($this->scan());
    }

    /**
     * Get modules by status.
     *
     * @param $status
     *
     * @return array
     */
    public function getByStatus($status)
    {
        $modules = [];

        foreach ($this->all() as $name => $module) {
            if ($module->isStatus($status)) {
                $modules[$name] = $module;
            }
        }

        return $modules;
    }

    /**
     * Determine whether the given module exist.
     *
     * @param $name
     *
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->all());
    }

    /**
     * Get list of enabled modules.
     *
     * @return array
     */
    public function enabled()
    {
        return $this->getByStatus(1);
    }

    /**
     * Get list of disabled modules.
     *
     * @return array
     */
    public function disabled()
    {
        return $this->getByStatus(0);
    }

    /**
     * Get count from all modules.
     *
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * Get a module path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path ?: $this->config('paths.modules');
    }

    /**
     * Register the modules.
     */
    public function registerAll()
    {
        foreach ($this->enabled() as $module) {
            $module->register();
        }
    }

    /**
     * Boot the modules.
     */
    public function bootAll()
    {
        foreach ($this->enabled() as $module) {
            $module->boot();
        }
    }

    /**
     * Find a specific module.
     *
     * @param $name
     */
    public function find($name)
    {
        foreach ($this->all() as $module) {
            if ($module->getLowerName() == strtolower($name)) {
                return $module;
            }
        }

        return;
    }

    /**
     * Alternative for "find" method.
     *
     * @param $name
     */
    public function get($name)
    {
        return $this->find($name);
    }

    /**
     * Find a specific module, if there return that, otherwise throw exception.
     *
     * @param $name
     *
     * @return Module
     *
     * @throws ModuleNotFoundException
     */
    public function findOrFail($name)
    {
        if (! is_null($module = $this->find($name))) {
            return $module;
        }

        throw new ModuleNotFoundException("Module [{$name}] does not exist!");
    }

    /**
     * Get all modules as laravel collection instance.
     *
     * @return Collection
     */
    public function collections()
    {
        return collect($this->enabled());
    }

    /**
     * Get module path for a specific module.
     *
     * @param $module
     *
     * @return string
     */
    public function getModulePath($module)
    {
        try {
            return $this->findOrFail($module)->getPath().'/';
        } catch (ModuleNotFoundException $e) {
            return $this->getPath().'/'.studly_case($module).'/';
        }
    }

    /**
     * Get asset path for a specific module.
     *
     * @param $module
     *
     * @return string
     */
    public function assetPath($module)
    {
        return $this->config('paths.assets').'/'.$module;
    }

    /**
     * Get a specific config data from a configuration file.
     *
     * @param $key
     *
     * @return mixed
     */
    public function config($key)
    {
        return config('modules.'.$key);
    }

    /**
     * Get module assets path.
     *
     * @return string
     */
    public function getAssetsPath()
    {
        return $this->config('paths.assets');
    }

    /**
     * Get asset url from a specific module.
     *
     * @param string $asset
     * @param bool   $secure
     *
     * @return string
     */
    public function asset($asset)
    {
        list($name, $url) = explode(':', $asset);

        $baseUrl = str_replace(public_path(), '', $this->getAssetsPath());

        $url = public_asset($baseUrl."/{$name}/".$url);

        return str_replace(['http://', 'https://'], '//', $url);
    }

    /**
     * Determine whether the given module is activated.
     *
     * @param string $name
     *
     * @return bool
     */
    public function active($name)
    {
        return $this->findOrFail($name)->active();
    }

    /**
     * Determine whether the given module is not activated.
     *
     * @param string $name
     *
     * @return bool
     */
    public function notActive($name)
    {
        return ! $this->active($name);
    }

    /**
     * Enabling a specific module.
     *
     * @param string $name
     *
     * @return bool
     */
    public function enable($name)
    {
        return $this->findOrFail($name)->enable();
    }

    /**
     * Disabling a specific module.
     *
     * @param string $name
     *
     * @return bool
     */
    public function disable($name)
    {
        return $this->findOrFail($name)->disable();
    }

    /**
     * Delete a specific module.
     *
     * @param string $name
     *
     * @return bool
     */
    public function delete($name)
    {
        return $this->findOrFail($name)->delete();
    }

    /**
     * Get stub path.
     *
     * @return string
     */
    public function getStubPath()
    {
        if (! is_null($this->stubPath)) {
            return $this->stubPath;
        }

        return $this->stubPath;
    }

    /**
     * Set stub path.
     *
     * @param string $stubPath
     *
     * @return $this
     */
    public function setStubPath($stubPath)
    {
        $this->stubPath = $stubPath;

        return $this;
    }
}
