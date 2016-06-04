<?php

namespace ZEDx\Repositories;

use Countable;
use File;
use ZEDx\Components\Map;
use ZEDx\Exceptions\MapNotFoundException;
use ZEDx\Models\Country;
use ZEDx\Support\Json;

class MapsRepository implements Countable
{
    /**
     * The map path.
     *
     * @var string|null
     */
    protected $path;

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
     * Get scanned maps paths.
     *
     * @return array
     */
    protected function getScanPath()
    {
        return $this->getPath().'/*';
    }

    /**
     * Get & scan all maps.
     *
     * @return array
     */
    protected function scan($group = null)
    {
        $path = $this->getScanPath();
        $maps = [];

        $manifests = File::glob("{$path}.json");

        is_array($manifests) || $manifests = [];

        foreach ($manifests as $manifest) {
            $code = strtoupper(basename($manifest, '.json'));
            $maps[$code] = new Map($code);
        }

        return $maps;
    }

    /**
     * Get maps by status.
     *
     * @param $status
     *
     * @return array
     */
    public function getByStatus($status)
    {
        $maps = [];

        $enabled = Country::enabled()->lists('code')->toArray();

        foreach ($this->all() as $code => $map) {
            if (in_array($code, $enabled) === $status) {
                $maps[$code] = $map;
            }
        }

        return $maps;
    }

    /**
     * Get list of enabled maps.
     *
     * @return array
     */
    public function enabled()
    {
        return $this->getByStatus(true);
    }

    /**
     * Get list of disabled maps.
     *
     * @return array
     */
    public function disabled()
    {
        return $this->getByStatus(false);
    }

    /**
     * Get all maps.
     *
     * @return array
     */
    public function all()
    {
        return $this->scan();
    }

    /**
     * Get all maps as collection instance.
     *
     * @return Collection
     */
    public function toCollection()
    {
        return collect($this->scan());
    }

    /**
     * Get default map.
     *
     * @return string
     */
    public function getDefaultMap()
    {
        return collect($this->enabled())->keys()->first();
    }

    /**
     * Determine whether the given map exist.
     *
     * @param $code
     *
     * @return bool
     */
    public function has($code)
    {
        return array_key_exists($code, $this->all());
    }

    /**
     * Alternative for "find" method.
     *
     * @param $code
     *
     * @return bool
     */
    public function exists($code)
    {
        return $this->has($code);
    }

    /**
     * Get count from all maps.
     *
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * Get a map path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path ?: $this->config('path');
    }

    /**
     * Find a specific map.
     *
     * @param $code
     */
    public function find($code)
    {
        foreach ($this->all() as $map) {
            if ($map->getLowerCode() == strtolower($code)) {
                return $map;
            }
        }
    }

    /**
     * Alternative for "find" method.
     *
     * @param $code
     */
    public function get($code)
    {
        return $this->find($code);
    }

    /**
     * Find a specific map, if there return that, otherwise throw exception.
     *
     * @param $code
     *
     * @throws MapNotFoundException
     *
     * @return Map
     */
    public function findOrFail($code)
    {
        if (!is_null($map = $this->find($code))) {
            return $map;
        }

        throw new MapNotFoundException("Map [{$code}] does not exist!");
    }

    /**
     * Construct map attributes.
     *
     * @param string $code
     *
     * @return string
     */
    public function constructAttributes($code)
    {
        $map = $this->find($code);

        if (!$map) {
            return '';
        }

        $attrs = $map->getAttributes();
        $htmlAttrs = '';

        foreach ($attrs as $attr => $value) {
            $htmlAttrs .= "data-{$attr}={$value} ";
        }

        return $htmlAttrs;
    }

    /**
     * Determine whether the current map is valid or not.
     *
     * @return bool
     */
    public function isValidMapFile($mapFile)
    {
        try {
            $data = (new Json($mapFile))->getAttributes();

            return isset($data['attributes'])
            && isset($data['attributes']['fill'])
            && isset($data['attributes']['stroke'])
            && isset($data['attributes']['stroke-width'])
            && isset($data['height']) && is_numeric($data['height'])
            && isset($data['width']) && is_numeric($data['width'])
            && isset($data['animate'])
            && isset($data['animate']['attributes'])
            && isset($data['animate']['attributes']['fill'])
            && isset($data['paths']) && !empty($data['paths']);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get all maps as laravel collection instance.
     *
     * @return Collection
     */
    public function collections()
    {
        return collect($this->all());
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
        return config('maps.'.$key);
    }

    /**
     * Delete a specific map.
     *
     * @param string $code
     *
     * @return bool
     */
    public function delete($code)
    {
        return $this->findOrFail($code)->delete();
    }
}
