<?php

namespace ZEDx\Events\Menu;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Menu;

class MenuEvent extends Event
{
    use SerializesModels;

    /**
     * Menu model.
     *
     * @var Menu
     */
    public $menu;

    /**
     * Create a new event instance.
     *
     * @param \ZEDx\Models\Menu $menu
     *
     * @return void
     */
    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }
}
