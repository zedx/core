<?php

namespace ZEDx\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use ZEDx\Cache\ResponseCache;

class Cache
{
    /**
     * @var \ZEDx\Cache\ResponseCache
     */
    protected $responseCache;

    public function __construct(ResponseCache $responseCache)
    {
        $this->responseCache = $responseCache;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->responseCache->hasCached($request)) {
            return $this->responseCache->getCachedResponseFor($request);
        }

        $response = $next($request);

        if ($this->responseCache->shouldCache($request, $response)) {
            $this->responseCache->cacheResponse($request, $response);
        }

        return $response;
    }
}
