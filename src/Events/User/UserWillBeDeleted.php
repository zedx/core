<?php

namespace ZEDx\Events\User;

use ZEDx\Models\User;
use ZEDx\Events\Event;
use Illuminate\Queue\SerializesModels;

class UserWillBeDeleted extends Event
{
    use SerializesModels;

    public $user;
    public $actor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $actor)
    {
        $this->user = $user;
        $this->actor = $actor;
    }
}
