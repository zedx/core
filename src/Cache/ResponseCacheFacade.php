<?php

namespace ZEDx\Cache;

use Illuminate\Support\Facades\Facade;

class ResponseCacheFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'zedx-cache';
    }
}
