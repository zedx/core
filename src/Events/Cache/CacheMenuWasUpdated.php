<?php

namespace ZEDx\Events\Cache;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Menu;

class CacheMenuWasUpdated extends Event
{
    use SerializesModels;

    public $menu;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }
}
