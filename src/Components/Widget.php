<?php

namespace ZEDx\Components;

use ZEDx\Support\Json;

abstract class Widget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * The Widget type.
     *
     * @var string
     */
    protected $type;

    /**
     * The widget author.
     *
     * @var string
     */
    protected $author;

    /**
     * The widget name.
     *
     * @var string
     */
    protected $name;

    /**
     * The widget path.
     *
     * @var string
     */
    protected $path;

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct($type, $author, $name, array $config = [])
    {
        $this->type = $type;
        $this->author = $author;
        $this->name = $name;

        $this->path = realpath(config('widgets.paths.widgets')
            .'/'.studly_case($type)
            .'/'.studly_case($author)
            .'/'.studly_case($name)
            );

        $this->setConfig($config);
    }

    /**
     * Boot Widget.
     *
     * @return void
     */
    abstract public function boot();

    /**
     * Run Widget.
     *
     * @return Response
     */
    abstract public function run();

    /**
     * Setting widget.
     *
     * @param  string $url
     *
     * @return Response
     */
    abstract public function setting($url);

    /**
     * Get lower widget fullname.
     *
     * @return string
     */
    final public function getLowerFullName()
    {
        return strtolower($this->getFullName());
    }

    /**
     * Get widget fullname.
     *
     * @return string
     */
    final public function getFullName()
    {
        return $this->type
            .'\\'.$this->author
            .'\\'.$this->name;
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
     * Set config.
     *
     * @param array $config
     */
    final public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get config.
     *
     * @return array $config
     */
    final public function getConfig()
    {
        return $this->config;
    }

    /**
     * Delete widget.
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
    final public function json($file = null)
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
