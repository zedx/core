<?php

namespace ZEDx\Events\Admin;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Admin;

class AdminWillBeUpdated extends Event
{
    use SerializesModels;

    public $admin;
    public $actor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Admin $admin, $actor)
    {
        $this->admin = $admin;
        $this->actor = $actor;
    }
}
