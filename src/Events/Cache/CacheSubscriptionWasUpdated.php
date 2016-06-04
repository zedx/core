<?php

namespace ZEDx\Events\Cache;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Subscription;

class CacheSubscriptionWasUpdated extends Event
{
    use SerializesModels;

    public $subscription;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }
}
