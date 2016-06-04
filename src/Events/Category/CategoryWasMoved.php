<?php

namespace ZEDx\Events\Category;

use ZEDx\Events\Event;
use ZEDx\Models\Category;
use Illuminate\Queue\SerializesModels;

class CategoryWasMoved extends Event
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
