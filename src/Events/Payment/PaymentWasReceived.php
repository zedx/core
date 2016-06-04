<?php

namespace ZEDx\Events\Payment;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Order;

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
