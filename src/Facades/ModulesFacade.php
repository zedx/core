<?php

namespace ZEDx\Facades;

use Illuminate\Support\Facades\Facade;

class ModulesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'modules';
    }
}
