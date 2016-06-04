<?php

namespace ZEDx\Repositories;

use Countable;
use File;
use ZEDx\Contracts\ComponentInterface;
use ZEDx\Exceptions\WidgetNotFoundException;
use ZEDx\Support\Json;

class WidgetsRepository implements ComponentInterface, Countable
{
    /**
     * The widget type.
     *
     * @var string|null
     */
    protected $type;

    /**
     * The widget scan filter.
     *
     * @var string|null
     */
    protected $filter;

    /**
     * The widget path.
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
     * Get scanned widgets paths.
     *
     * @return array
     */
    protected function getScanPath()
    {
        $type = '*';

        if ($this->type) {
            $type = $this->type;
        }

        return $this->getPath()."/{$type}/*/*";
    }

    /**
     * Set type to Frontend.
     *
     * @return $this
     */
    public function frontend()
    {
        $this->type = 'Frontend';

        return $this;
    }

    /**
     * Set type to Backend.
     *
     * @return $this
     */
    public function backend()
    {
        $this->type = 'Backend';

        return $this;
    }

    /**
     * Set type to null.
     *
     * @return $this
     */
    public function noType()
    {
        $this->type = null;

        return $this;
    }

    /**
     * Group widgets list by authors.
     *
     * @return $this
     */
    public function groupByAuthors()
    {
        $this->filter = 'authors';

        return $this;
    }

    /**
     * Group widgets list by authors.
     *
     * @return $this
     */
    public function noFilter()
    {
        $this->filter = null;

        return $this;
    }

    /**
     * Get & scan all widgets.
     *
     * @return array
     */
    protected function scan($group = null)
    {
        $path = $this->getScanPath();
        $widgets = [];

        $manifests = File::glob("{$path}/zedx.json");

        is_array($manifests) || $manifests = [];

        foreach ($manifests as $manifest) {
            $json = Json::make($manifest);

            $groups = $json->get('groups');

            if (!$this->allowedGroup($groups, $group)) {
                continue;
            }

            $studlyType = studly_case($json->get('type'));
            $studlyAuthor = studly_case($json->get('author'));
            $studlyName = studly_case($json->get('name'));

            $namespace = config('widgets.namespace');
            $fullName = "$studlyType\\$studlyAuthor\\$studlyName";
            $widget = "$namespace\\$fullName\\Widget";

            $widgetClass = new $widget($studlyType, $studlyAuthor, $studlyName, []);

            if (!$this->filter) {
                $widgets[$fullName] = $widgetClass;
                continue;
            }

            if ($this->filter == 'authors') {
                if (!isset($widgets[$studlyAuthor])) {
                    $widgets[$studlyAuthor][] = $widgetClass;
                } else {
                    array_push($widgets[$studlyAuthor], $widgetClass);
                }
            }
        }

        return $widgets;
    }

    /**
     * whether the given group is inside group list.
     *
     * @param array  $groups
     * @param string $group
     *
     * @return bool
     */
    protected function allowedGroup($groups, $group)
    {
        if ($this->type == 'Backend' || is_null($group) || empty($groups)) {
            return true;
        }

        return in_array($group, $groups);
    }

    /**
     * Get all widgets.
     *
     * @return array
     */
    public function all($group = null)
    {
        return $this->scan($group);
    }

    /**
     * Get all widgets as collection instance.
     *
     * @return Collection
     */
    public function toCollection()
    {
        return collect($this->scan());
    }

    /**
     * Determine whether the given widget exist.
     *
     * @param $fullName
     *
     * @return bool
     */
    public function has($fullName)
    {
        return array_key_exists($fullName, $this->noFilter()->all());
    }

    /**
     * Alternative for "find" method.
     *
     * @param $fullName
     *
     * @return bool
     */
    public function exists($fullName)
    {
        return $this->has($fullName);
    }

    /**
     * Get count from all widgets.
     *
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * Get a widget path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path ?: $this->config('paths.widgets');
    }

    /**
     * Register the widgets.
     */
    public function registerAll()
    {
        foreach ($this->noFilter()->all() as $widget) {
            $widget->register();
        }
    }

    /**
     * Boot the widgets.
     */
    public function bootAll()
    {
        foreach ($this->noFilter()->all() as $widget) {
            $widget->boot();
        }
    }

    /**
     * Find a specific widget.
     *
     * @param $fullName
     */
    public function find($fullName)
    {
        foreach ($this->noFilter()->all() as $widget) {
            if ($widget->getLowerFullName() == strtolower($fullName)) {
                return $widget;
            }
        }
    }

    /**
     * Alternative for "find" method.
     *
     * @param $fullName
     */
    public function get($fullName)
    {
        return $this->find($fullName);
    }

    /**
     * Find a specific widget, if there return that, otherwise throw exception.
     *
     * @param $fullName
     *
     * @throws WidgetNotFoundException
     *
     * @return Widget
     */
    public function findOrFail($fullName)
    {
        if (!is_null($widget = $this->find($fullName))) {
            return $widget;
        }

        throw new WidgetNotFoundException("Widget [{$fullName}] does not exist!");
    }

    /**
     * Get all widgets as laravel collection instance.
     *
     * @return Collection
     */
    public function collections()
    {
        return collect($this->all());
    }

    /**
     * Get widget path for a specific widget.
     *
     * @param $widget
     *
     * @return string
     */
    public function getWidgetPath($fullName)
    {
        try {
            return $this->findOrFail($fullName)->getPath().'/';
        } catch (WidgetNotFoundException $e) {
            return $this->getPath().'/'.$fullName.'/';
        }
    }

    /**
     * Get asset path for a specific widget.
     *
     * @param $widget
     *
     * @return string
     */
    public function assetPath($widget)
    {
        return $this->config('paths.assets').'/'.$widget;
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
        return config('widgets.'.$key);
    }

    /**
     * Get widget assets path.
     *
     * @return string
     */
    public function getAssetsPath()
    {
        return $this->config('paths.assets');
    }

    /**
     * Get asset url from a specific widget.
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
     * Run a specific widget.
     *
     * @param string $fullName
     *
     * @return Response
     */
    public function run($fullName, $config = [])
    {
        echo $this->findOrFail($fullName)->setConfig($config)->run();
    }

    /**
     * Setting a specific widget.
     *
     * @param string $fullName
     * @param string $url
     *
     * @return Response
     */
    public function setting($fullName, $config, $url)
    {
        echo $this->findOrFail($fullName)->setConfig($config)->setting($url);
    }

    /**
     * Set config for a specific widget.
     *
     * @param string $fullName
     * @param string $url
     *
     * @return Response
     */
    public function setConfig($fullName, $config = [])
    {
        return $this->findOrFail($fullName)->setConfig($config);
    }

    /**
     * Render a widget block.
     *
     * @param string $fullName
     * @param string $url
     *
     * @return Response
     */
    public function renderBlock($block, $vars)
    {
        $list = isset($vars['__zedx_template_blocks']) ? $vars['__zedx_template_blocks'] : [];

        if (empty($list) || empty($list[$block])) {
            return;
        }

        foreach ($list[$block] as $widget) {
            echo $this->findOrFail($widget['_namespace'])
                ->setConfig($widget['_config'])
                ->run();
        }
    }

    /**
     * Delete a specific widget.
     *
     * @param string $fullName
     *
     * @return bool
     */
    public function delete($fullName)
    {
        return $this->findOrFail($fullName)->delete();
    }

    /**
     * Get stub path.
     *
     * @return string
     */
    public function getStubPath()
    {
        if (!is_null($this->stubPath)) {
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
