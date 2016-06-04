<?php

namespace ZEDx\Cache;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheProfile
{
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    public function __construct()
    {
        $this->app = app();
    }

    /**
     * Determine if the app is running in the console.
     *
     * To allow testing this will return false the environment is testing.
     *
     * @return bool
     */
    public function isRunningInConsole()
    {
        if ($this->app->environment('testing')) {
            return false;
        }

        return $this->app->runningInConsole();
    }

    /**
     * Determine if the given request should be cached;.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function shouldCacheRequest(Request $request)
    {
        if ($request->ajax()) {
            return false;
        }

        if ($this->isRunningInConsole()) {
            return false;
        }

        return $request->isMethod('get');
    }

    /**
     * Determine if the given response should be cached.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return bool
     */
    public function shouldCacheResponse(Response $response)
    {
        return $response->isSuccessful() || $response->isRedirection();
    }

    /**
     * Set a string to add to differentiate this request from others.
     *
     * @return string
     */
    public function cacheNameSuffix(Request $request)
    {
        if ($this->app->auth->check()) {
            return $this->app->auth->user()->id;
        }

        return '';
    }
}
