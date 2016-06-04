<?php

namespace ZEDx\Jobs;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Adtype\AdtypeWasPurchased;
use ZEDx\Models\Order;

class purchaseAdtype extends Job
{
    use SerializesModels;

    public $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->order->status != 'completed') {
            return;
        }

        $user = $this->order->user;
        $quantity = $this->order->quantity;
        $adtypeId = $this->order->transaction->item_id;
        $adtype = $user->adtypes()->whereAdtypeId($adtypeId)->first();
        $number = $adtype->pivot->number + $quantity;
        $user->adtypes()->updateExistingPivot($adtypeId, ['number' => $number]);

        event(
            new AdtypeWasPurchased($adtype, $this->order)
        );
    }
}
