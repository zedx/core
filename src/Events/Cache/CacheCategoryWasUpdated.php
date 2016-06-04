<?php

namespace ZEDx\Events\Cache;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Category;

class CacheCategoryWasUpdated extends Event
{
    use SerializesModels;

    public $category;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }
}
