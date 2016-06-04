<?php

namespace ZEDx\Components;

use Maps;
use ZEDx\Models\Country;
use ZEDx\Support\Json;

class Map
{
    /**
     * The map code.
     *
     * @var string
     */
    protected $code;

    /**
     * The map path.
     *
     * @var string
     */
    protected $path;

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct($code)
    {
        $this->code = $code;
        $this->path = Maps::getPath().'/'.$code.'.json';
    }

    /**
     * Get lower map code.
     *
     * @return string
     */
    final public function getLowerCode()
    {
        return strtolower($this->getCode());
    }

    /**
     * Get map code.
     *
     * @return string
     */
    final public function getCode()
    {
        return $this->code;
    }

    /**
     * Get path.
     *
     * @return string
     */
    final public function getPath()
    {
        return $this->path;
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
        return (bool) Country::whereCode($this->getCode())
            ->whereIsActivate($status)->count();
    }

    /**
     * Determine whether the current map is enabled.
     *
     * @return bool
     */
    public function enabled()
    {
        return $this->isStatus(true);
    }

    /**
     * Determine whether the current map is disabled.
     *
     * @return bool
     */
    public function disabled()
    {
        return $this->isStatus(false);
    }

    /**
     * Get map attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        $data = $this->json()->getAttributes();
        $attributes = $data['attributes'];
        $attributes['animate-fill'] = $data['animate']['attributes']['fill'];

        return $attributes;
    }

    /**
     * Set map attributes.
     *
     * @param array $attributes
     */
    public function setAttributes($attributes)
    {
        $json = $this->json();
        $data = $json->getAttributes();

        $data['animate']['attributes']['fill'] = $attributes['animate-fill'];
        unset($attributes['animate-fill']);
        $data['attributes'] = $attributes + $data['attributes'];

        $json->update($data);
    }

    /**
     * Delete map.
     *
     * @return bool
     */
    final public function delete()
    {
        //
    }

    /**
     * Get json contents.
     *
     * @return Json
     */
    final public function json()
    {
        return new Json($this->getPath());
    }

    /**
     * Get a specific data from json file by given the key.
     *
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    final public function get($key, $default = null)
    {
        return $this->json()->get($key, $default);
    }

    /**
     * Handle call to __get method.
     *
     * @param $key
     *
     * @return mixed
     */
    final public function __get($key)
    {
        return $this->get($key);
    }
}
