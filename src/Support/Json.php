<?php

namespace ZEDx\Support;

use File;

class Json
{
    /**
     * The file path.
     *
     * @var string
     */
    protected $path;

    /**
     * The attributes collection.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $attributes;

    /**
     * The constructor.
     *
     * @param mixed                             $path
     */
    public function __construct($path)
    {
        $this->path = (string) $path;
        $this->attributes = collect($this->getAttributes());
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
     * @param mixed $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = (string) $path;

        return $this;
    }

    /**
     * Make new instance.
     *
     * @param string                            $path
     *
     * @return static
     */
    public static function make($path)
    {
        return new static($path);
    }

    /**
     * Get file content.
     *
     * @return string
     */
    public function getContents()
    {
        return File::get($this->getPath());
    }

    /**
     * Get file contents as array.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->json_clean_decode($this->getContents(), true);
    }

    /**
     * Clean comments of json content and decode it with json_decode().
     * Work like the original php json_decode() function with the same params.
     *
     * @param   string  $json    The json string being decoded
     * @param   bool    $assoc   When TRUE, returned objects will be converted into associative arrays.
     * @param   int $depth   User specified recursion depth. (>=5.3)
     * @param   int $options Bitmask of JSON decode options. (>=5.4)
     *
     * @return  array/object
     */
    public function json_clean_decode($json, $assoc = false, $depth = 512, $options = 0)
    {
        // search and remove comments like /* */ and //
        $json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $json);
        if (version_compare(phpversion(), '5.4.0', '>=')) {
            return json_decode($json, $assoc, $depth, $options);
        } elseif (version_compare(phpversion(), '5.3.0', '>=')) {
            return json_decode($json, $assoc, $depth);
        } else {
            return json_decode($json, $assoc);
        }
    }

    /**
     * Convert the given array data to pretty json.
     *
     * @param array $data
     *
     * @return string
     */
    public function toJsonPretty(array $data = null)
    {
        return json_encode($data ?: $this->attributes, JSON_PRETTY_PRINT);
    }

    /**
     * Update json contents from array data.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $this->attributes = collect(array_merge($this->attributes->toArray(), $data));

        return $this->save();
    }

    /**
     * Set a specific key & value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->attributes->offsetSet($key, $value);

        return $this;
    }

    /**
     * Save the current attributes array to the file storage.
     *
     * @return bool
     */
    public function save()
    {
        return File::put($this->getPath(), $this->toJsonPretty());
    }

    /**
     * Handle magic method __get.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Get the specified attribute from json file.
     *
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->attributes->get($key, $default);
    }

    /**
     * Handle call to __call method.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments = [])
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $arguments);
        }

        return call_user_func_array([$this->attributes, $method], $arguments);
    }

    /**
     * Handle call to __toString method.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getContents();
    }
}
