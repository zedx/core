<?php

namespace ZEDx\Components;

use File;
use Illuminate\Foundation\AliasLoader;
use ZEDx\Managers\ComposerManager;
use ZEDx\Support\Json;

class Module
{
    /**
     * If module already registered.
     *
     * @var
     */
    protected $registered = false;

    /**
     * If module already booted.
     *
     * @var
     */
    protected $booted = false;

    /**
     * The module name.
     *
     * @var
     */
    protected $name;

    /**
     * The module path,.
     *
     * @var string
     */
    protected $path;

    /**
     * The constructor.
     *
     * @param Application $app
     * @param $name
     * @param $path
     */
    public function __construct($name, $path)
    {
        $this->name = $name;
        $this->path = realpath($path);
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get name in lower case.
     *
     * @return string
     */
    public function getLowerName()
    {
        return strtolower($this->name);
    }

    /**
     * Get name in studly case.
     *
     * @return string
     */
    public function getStudlyName()
    {
        return studly_case($this->name);
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->get('description');
    }

    /**
     * Get alias.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->get('alias');
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path.
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        if ($this->booted) {
            return false;
        }

        $this->registerTranslation();

        $this->fireEvent('boot');

        $this->booted = true;
    }

    /**
     * Register module's translation.
     *
     * @return void
     */
    protected function registerTranslation()
    {
        $lowerName = $this->getLowerName();

        $langPath = base_path("resources/lang/{$lowerName}");

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $lowerName);
        }
    }

    /**
     * Get json contents.
     *
     * @return Json
     */
    public function json($file = null)
    {
        if (is_null($file)) {
            $file = 'zedx.json';
        }

        return new Json($this->getPath().'/'.$file);
    }

    /**
     * Get a specific data from json file by given the key.
     *
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->json()->get($key, $default);
    }

    /**
     * Get a specific data from composer.json file by given the key.
     *
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    public function getComposerAttr($key, $default = null)
    {
        return $this->json('composer.json')->get($key, $default);
    }

    /**
     * Register the module.
     */
    public function register()
    {
        if ($this->registered) {
            return;
        }

        $this->registerComposerDependencies();

        $this->registerAliases();

        $this->registerProviders();

        $this->registerFiles();

        $this->fireEvent('register');

        $this->registered = true;
    }

    /**
     * Register the module event.
     *
     * @param string $event
     */
    protected function fireEvent($event)
    {
        event(sprintf('modules.%s.'.$event, $this->getLowerName()), [$this]);
    }

    /*
     * Register plugin class autoloaders
     */
    protected function registerComposerDependencies()
    {
        $autoloadPath = $this->path.'/vendor/autoload.php';
        if (File::isFile($autoloadPath)) {
            ComposerManager::instance()->autoload($this->path.'/vendor');
        }
    }

    /**
     * Register the aliases from this module.
     */
    protected function registerAliases()
    {
        $loader = AliasLoader::getInstance();
        foreach ($this->get('aliases', []) as $aliasName => $aliasClass) {
            $loader->alias($aliasName, $aliasClass);
        }
    }

    /**
     * Register the service providers from this module.
     */
    protected function registerProviders()
    {
        foreach ($this->get('providers', []) as $provider) {
            app()->register($provider);
        }
    }

    /**
     * Register the files from this module.
     */
    protected function registerFiles()
    {
        foreach ($this->get('files', []) as $file) {
            include $this->path.'/'.$file;
        }
    }

    /**
     * Handle call __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getStudlyName();
    }

    /**
     * Determine whether the given status same with the current module status.
     *
     * @param $status
     *
     * @return bool
     */
    public function isStatus($status)
    {
        return $this->get('active', 0) == $status;
    }

    /**
     * Determine whether the current module activated.
     *
     * @return bool
     */
    public function enabled()
    {
        return $this->active();
    }

    /**
     * Alternate for "enabled" method.
     *
     * @return bool
     */
    public function active()
    {
        return $this->isStatus(1);
    }

    /**
     * Determine whether the current module not activated.
     *
     * @return bool
     */
    public function notActive()
    {
        return ! $this->active();
    }

    /**
     * Alias for "notActive" method.
     *
     * @return bool
     */
    public function disabled()
    {
        return ! $this->enabled();
    }

    /**
     * Set active state for current module.
     *
     * @param $active
     *
     * @return bool
     */
    public function setActive($active)
    {
        return $this->json()->set('active', $active)->save();
    }

    /**
     * Disable the current module.
     *
     * @return bool
     */
    public function disable()
    {
        event('module.disabling', [$this]);

        $this->setActive(0);

        event('module.disabled', [$this]);
    }

    /**
     * Enable the current module.
     */
    public function enable()
    {
        event('module.enabling', [$this]);

        $this->setActive(1);

        event('module.enabled', [$this]);
    }

    /**
     * Get extra path.
     *
     * @param $path
     *
     * @return string
     */
    public function getExtraPath($path)
    {
        return $this->getPath().'/'.$path;
    }

    /**
     * Handle call to __get method.
     *
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }
}
