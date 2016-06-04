<?php

namespace ZEDx\Jobs;

use ZEDx\Models\Order;
use ZEDx\Models\Subscription;
use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Subscription\SubscriptionWasPurchased;

class purchaseSubscription extends Job
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

        $subscriptionId = $this->order->transaction->item_id;
        $subscription = Subscription::findOrFail($subscriptionId);

        event(
            new SubscriptionWasPurchased($subscription, $this->order)
        );

        dispatch(
            new swapSubscription($subscription, $this->order->user)
        );
    }
}
