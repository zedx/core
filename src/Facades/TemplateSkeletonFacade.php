<?php

namespace ZEDx\Facades;

use Illuminate\Support\Facades\Facade;

class TemplateSkeletonFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'TemplateSkeleton';
    }
}
