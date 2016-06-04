<?php

namespace ZEDx\Cache;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use ZEDx\Widget;

class CacheHasher
{
    /**
     * Get a hash value for the given request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */

    /*
    public function getHashFor(Request $request)
    {
    return 'zedx-cache-'.md5(
    "{$request->getUri()}/{$request->getMethod()}/".$this->cacheProfile->cacheNameSuffix($request)
    );
    }
     */

    public function getHashForWidget(Widget $widget)
    {
        return [
        'tags' => $this->getWidgetCacheTags($widget),
        'key'  => $this->getWidgetCacheKey($widget),
      ];
    }

    public function getWidgetCacheTags(Widget $widget)
    {
        return array_merge(
        ['widgets', $this->getWidgetCacheTag($widget)],
        $this->getWidgetCacheParams($widget)
      );
    }

    public function getWidgetCacheTag(Widget $widget)
    {
        return 'widget-tag-'.md5(get_class($widget));
    }

    public function getWidgetCacheKey(Widget $widget)
    {
        $key = [];

        foreach ($widget->cacheKeyDependences() as $dependence) {
            $key[] = $this->getHashKeyDependences($dependence);
        }

        return 'widget-key-'.md5(get_class($widget).'-'.json_encode($key));
    }

    private function getWidgetCacheParams(Widget $widget)
    {
        $key = [];

        foreach ($widget->cacheKeyDependences() as $dependence) {
            $key[] = $this->getHashKeyDependences($dependence);
        }

        $models = array_pluck($key, 'model');

        return array_filter($models);
    }

    private function getHashKeyDependences($dependence)
    {
        $dependence = explode(':', $dependence);

        $type = head($dependence);
        $element = last($dependence);

        if ($type == 'query') {
            return $this->getHashQuery($element);
        } elseif ($type == 'parameter') {
            return $this->getHashParameter($element);
        }

        return [];
    }

    private function getHashQuery($elements)
    {
        $key = [
        'query' => [],
      ];

        foreach (explode(',', $elements) as $queryName) {
            $key['query'] = $queryName.'='.\Request::get($queryName);
        }

        return $key;
    }

    /*
     * On peut également récupérer la liste des listeners depuis le Model et créer ensuite des tags pour
     * chaque Model.
     * Exemple :
     * Ad $cacheListen = [
     *   'category_id' => Category::class,
     *   'status_id' => Adstatus::class
     * ]
     *
     * Ensuite je crée md5(Ad::class) . 'category_id' . '.' . 3
     *
     */

    private function getHashParameter($parameterName)
    {
        $key = [
        'model'     => null,
        'parameter' => null,
      ];

        $parameter = \Request::route()->parameter($parameterName);

        if ($parameter === null) {
            return $key;
        }

        if ($parameter instanceof Model) {
            $key['model'] = md5(get_class($parameter)).'.id.'.$parameter->id;
        } else {
            $key['parameter'] = $parameterName.'='.$parameter;
        }

        return $key;
    }

    public function getHashForRequest(Request $request)
    {
        $key = ['zx'];
        $subKey = [];

        $key[] = $request->route()->getName();
        $routeParams = $request->route()->parameters();

        foreach ($routeParams as $name => $value) {
            if ($value instanceof Model) {
                $subKey[] = $name.'.'.$value->id.'.'.strtotime($value->updated_at);
                $subKey[] = $value->getCacheRelationsKey();
            } elseif (is_string($value)) {
                $subKey[] = $name.'.'.$value;
            }
        }

        $key[] = implode('-', $subKey);

        return [
            'tags' => 'request',
            'key'  => implode(':', $key),
        ];
    }
}
