<?php

namespace ZEDx\Http\Middleware;

use Closure;

class DoNotCache
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->attributes->add(['zedx-cache.doNotCache' => true]);

        return $next($request);
    }
}
