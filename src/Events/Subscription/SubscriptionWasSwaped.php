<?php

namespace ZEDx\Events\Subscription;

use ZEDx\Models\User;
use ZEDx\Events\Event;
use ZEDx\Models\Subscription;
use Illuminate\Queue\SerializesModels;

class SubscriptionWasSwaped extends Event
{
    use SerializesModels;

    public $subscription;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription, User $user)
    {
        $this->subscription = $subscription;
        $this->user = $user;
    }
}
