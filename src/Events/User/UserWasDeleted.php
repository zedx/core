<?php

namespace ZEDx\Events\User;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\User;

class UserWasDeleted extends Event
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
