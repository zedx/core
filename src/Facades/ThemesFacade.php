<?php

namespace ZEDx\Facades;

use Illuminate\Support\Facades\Facade;

class ThemesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'themes';
    }
}
