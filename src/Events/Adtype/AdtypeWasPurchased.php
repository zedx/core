<?php

namespace ZEDx\Events\Adtype;

use ZEDx\Events\Event;
use ZEDx\Models\Order;
use ZEDx\Models\Adtype;
use Illuminate\Queue\SerializesModels;

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
