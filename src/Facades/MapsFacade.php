<?php

namespace ZEDx\Facades;

use Illuminate\Support\Facades\Facade;

class MapsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'maps';
    }
}
