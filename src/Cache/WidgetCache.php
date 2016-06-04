<?php

namespace ZEDx\Cache;

use Cache;
use ZEDx\Widget;
use Symfony\Component\HttpFoundation\Response;

class WidgetCache
{
    /**
     * @var ResponseCacheRepository
     */
    protected $cache;

    /**
     * @var CacheHasher
     */
    protected $hasher;

    /**
     * @var CacheProfile
     */
    protected $cacheProfile;

    /**
     * @param \ZEDx\Cache\CacheHasher                  $hasher
     * @param \ZEDx\Cache\CacheProfile                  $cacheProfile
     */
    public function __construct()
    {
        $this->cache = new ResponseCacheRepository();
        $this->hasher = new CacheHasher();
        $this->cacheProfile = new CacheProfile();
    }

    /**
     * Determine if the given widget should be cached.
     *
     * @param \ZEDx\Widget                                  $widget
     * @param \Symfony\Component\HttpFoundation\Response    $response
     *
     * @return bool
     */
    public function shouldCache(Widget $widget, Response $response)
    {
        if (! env('APP_CACHE', true)) {
            return false;
        }

        if (! $widget->cache()) {
            return false;
        }

        return $this->cacheProfile->shouldCacheResponse($response);
    }

    /**
     * Store the given response in the cache.
     *
     * @param \ZEDx\Widget                                  $widget
     * @param \Symfony\Component\HttpFoundation\Response    $response
     */
    public function cacheWidget(Widget $widget, Response $response)
    {
        $this->cache->forever($this->hasher->getHashForWidget($widget), $response);
    }

    /**
     * Determine if the given widget has been cached.
     *
     * @param \ZEDx\Widget $widget
     *
     * @return bool
     */
    public function hasCached(Widget $widget)
    {
        if (! env('APP_CACHE', true)) {
            return false;
        }

        if (! $widget->cache()) {
            return false;
        }

        return $this->cache->has($this->hasher->getHashForWidget($widget));
    }

    /**
     * Get the cached response for the given widget.
     *
     * @param \ZEDx\Widget $widget
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getCachedResponseFor(Widget $widget)
    {
        return $this->cache->get($this->hasher->getHashForWidget($widget));
    }

    /**
     *  Flush the cache.
     */
    public function flush()
    {
        $this->cache->flush();
    }
}
