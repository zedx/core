<?php

namespace ZEDx\Models;

use Baum\Node;
use ZEDx\Events\Menu\MenuWasDeleted;
use ZEDx\Events\Menu\MenuWasMoved;

class Menu extends Node
{
    protected $fillable = [
        'name', 'title', 'group_name',
        'link', 'type', 'icon',
    ];

    protected static function boot()
    {
        parent::boot();

        static::moved(function ($menu) {
            event(new MenuWasMoved($menu));
        });

        static::deleted(function ($menu) {
            event(new MenuWasDeleted($menu));
        });
    }
}
