<?php

namespace ZEDx\Cache;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseCache
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
     * @param \ZEDx\Cache\CacheHasher               $hasher
     * @param \ZEDx\Cache\CacheProfile               $cacheProfile
     */
    public function __construct(ResponseCacheRepository $cache, CacheHasher $hasher, CacheProfile $cacheProfile)
    {
        $this->cache = $cache;
        $this->hasher = $hasher;
        $this->cacheProfile = $cacheProfile;
    }

    /**
     * Determine if the given request should be cached.
     *
     * @param \Illuminate\Http\Request                   $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return bool
     */
    public function shouldCache(Request $request, Response $response)
    {
        if (! env('APP_CACHE', true)) {
            return false;
        }

        if ($request->attributes->has('zedx-cache.doNotCache')) {
            return false;
        }

        if (! $this->cacheProfile->shouldCacheRequest($request)) {
            return false;
        }

        return $this->cacheProfile->shouldCacheResponse($response);
    }

    /**
     * Store the given response in the cache.
     *
     * @param \Illuminate\Http\Request                   $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function cacheResponse(Request $request, Response $response)
    {
        $response->headers->set('zedx-cache', 'cached on '.date('Y-m-d H:i:s'));
        $this->cache->forever($this->hasher->getHashForRequest($request), $response);
    }

    /**
     * Determine if the given request has been cached.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function hasCached(Request $request)
    {
        if (! env('APP_CACHE', true)) {
            return false;
        }

        return $this->cache->has($this->hasher->getHashForRequest($request));
    }

    /**
     * Get the cached response for the given request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getCachedResponseFor(Request $request)
    {
        return $this->cache->get($this->hasher->getHashForRequest($request));
    }

    /**
     *  Flush the cache.
     */
    public function flush()
    {
        $this->cache->flush();
    }
}
