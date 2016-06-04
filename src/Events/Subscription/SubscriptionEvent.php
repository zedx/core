<?php

namespace ZEDx\Events\Subscription;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Subscription;

class SubscriptionEvent extends Event
{
    use SerializesModels;

    /**
     * Subscription model.
     *
     * @var Subscription
     */
    public $subscription;

    /**
     * Create a new event instance.
     *
     * @param \ZEDx\Models\Subscription $subscription
     *
     * @return void
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }
}
