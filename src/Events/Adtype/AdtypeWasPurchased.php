<?php

namespace ZEDx\Events\Adtype;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Adtype;
use ZEDx\Models\Order;

class AdtypeWasPurchased extends Event
{
    use SerializesModels;

    public $adtype;
    public $order;
    public $actor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Adtype $adtype, Order $order)
    {
        $this->adtype = $adtype;
        $this->order = $order;
        $this->actor = $order->user;
    }
}
