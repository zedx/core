<?php

namespace ZEDx\Events\Payment;

use ZEDx\Models\Order;
use ZEDx\Events\Event;
use Illuminate\Queue\SerializesModels;

class PaymentWasReceived extends Event
{
    use SerializesModels;

    public $order;
    public $actor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order, $actor)
    {
        $this->order = $order;
        $this->actor = $actor;
    }
}
