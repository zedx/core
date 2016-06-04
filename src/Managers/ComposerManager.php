<?php

namespace ZEDx\Managers;

class ComposerManager
{
    protected static $instance;

    protected $namespacePool = [];

    protected $psr4Pool = [];

    protected $classMapPool = [];

    protected $includeFilesPool = [];

    /**
     * @var Composer\Autoload\ClassLoader The primary composer instance.
     */
    protected $loader;

    /**
     * Create a new instance of this singleton.
     */
    final public static function instance()
    {
        return isset(static::$instance)
            ? static::$instance
            : static::$instance = new static();
    }

    /**
     * Constructor.
     */
    final protected function __construct()
    {
        $this->loader = include base_path().'/vendor/autoload.php';
        $this->preloadPools();
    }

    public function __clone()
    {
        trigger_error('Cloning '.__CLASS__.' is not allowed.', E_USER_ERROR);
    }

    public function __wakeup()
    {
        trigger_error('Unserializing '.__CLASS__.' is not allowed.', E_USER_ERROR);
    }

    protected function preloadPools()
    {
        $this->classMapPool = array_fill_keys(array_keys($this->loader->getClassMap()), true);
        $this->namespacePool = array_fill_keys(array_keys($this->loader->getPrefixes()), true);
        $this->psr4Pool = array_fill_keys(array_keys($this->loader->getPrefixesPsr4()), true);
        $this->includeFilesPool = $this->preloadIncludeFilesPool();
    }

    protected function preloadIncludeFilesPool()
    {
        $result = [];
        $vendorPath = base_path().'/vendor';

        if (file_exists($file = $vendorPath.'/composer/autoload_files.php')) {
            $includeFiles = require $file;
            foreach ($includeFiles as $includeFile) {
                $relativeFile = $this->stripVendorDir($includeFile, $vendorPath);
                $result[$relativeFile] = true;
            }
        }

        return $result;
    }

    /**
     * Similar function to including vendor/autoload.php.
     *
     * @param string $vendorPath Absoulte path to the vendor directory.
     *
     * @return void
     */
    public function autoload($vendorPath)
    {
        $dir = $vendorPath.'/composer';

        if (file_exists($file = $dir.'/autoload_namespaces.php')) {
            $map = require $file;
            foreach ($map as $namespace => $path) {
                if (isset($this->namespacePool[$namespace])) {
                    continue;
                }
                $this->loader->set($namespace, $path);
                $this->namespacePool[$namespace] = true;
            }
        }

        if (file_exists($file = $dir.'/autoload_psr4.php')) {
            $map = require $file;
            foreach ($map as $namespace => $path) {
                if (isset($this->psr4Pool[$namespace])) {
                    continue;
                }
                $this->loader->setPsr4($namespace, $path);
                $this->psr4Pool[$namespace] = true;
            }
        }

        if (file_exists($file = $dir.'/autoload_classmap.php')) {
            $classMap = require $file;
            if ($classMap) {
                $classMapDiff = array_diff_key($classMap, $this->classMapPool);
                $this->loader->addClassMap($classMapDiff);
                $this->classMapPool += array_fill_keys(array_keys($classMapDiff), true);
            }
        }

        if (file_exists($file = $dir.'/autoload_files.php')) {
            $includeFiles = require $file;
            foreach ($includeFiles as $includeFile) {
                $relativeFile = $this->stripVendorDir($includeFile, $vendorPath);
                if (isset($this->includeFilesPool[$relativeFile])) {
                    continue;
                }
                require $includeFile;
                $this->includeFilesPool[$relativeFile] = true;
            }
        }
    }

    /**
     * Removes the vendor directory from a path.
     *
     * @param string $path
     *
     * @return string
     */
    protected function stripVendorDir($path, $vendorDir)
    {
        $path = realpath($path);
        $vendorDir = realpath($vendorDir);

        if (strpos($path, $vendorDir) === 0) {
            $path = substr($path, strlen($vendorDir));
        }

        return $path;
    }
}
