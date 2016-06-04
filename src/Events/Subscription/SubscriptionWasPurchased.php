<?php

namespace ZEDx\Events\Subscription;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Order;
use ZEDx\Models\Subscription;

class SubscriptionWasPurchased extends Event
{
    use SerializesModels;

    public $subscription;
    public $order;
    public $actor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription, Order $order)
    {
        $this->subscription = $subscription;
        $this->order = $order;
        $this->actor = $order->user;
    }
}
