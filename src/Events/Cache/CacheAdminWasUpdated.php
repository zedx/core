<?php

namespace ZEDx\Events\Cache;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Admin;

class CacheAdminWasUpdated extends Event
{
    use SerializesModels;

    public $admin;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }
}
