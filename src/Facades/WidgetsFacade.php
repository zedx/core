<?php

namespace ZEDx\Facades;

use Illuminate\Support\Facades\Facade;

class WidgetsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'widgets';
    }
}
