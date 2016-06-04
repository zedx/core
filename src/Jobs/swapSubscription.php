<?php

namespace ZEDx\Jobs;

use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Subscription\SubscriptionWasSwaped;
use ZEDx\Models\Subscription;
use ZEDx\Models\User;

class swapSubscription extends Job
{
    use SerializesModels;

    public $subscription;
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription, User $user)
    {
        $this->subscription = $subscription;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $candidateAdTypes = $this->subscription->adtypes()->lists('number', 'adtype_id');
        $existingAdtypes = $this->user->adtypes()->lists('number', 'adtype_id');
        $existingSubscriptionId = $this->user->subscription->id;

        foreach ($candidateAdTypes as $id => $number) {
            $adtypes[$id] = ['number' => $number];
        }

        if (empty($candidateAdTypes)) {
            return;
        }

        $this->user->adtypes()->sync($adtypes);
        $this->user->subscription()->associate($this->subscription)->save();
        $this->user->subscribed_at = Carbon::now()->format('d/m/Y');
        $this->user->subscription_expired_at = $this->subscription->days >= 9999 ? null : Carbon::createFromFormat('d/m/Y', $this->user->subscribed_at)
            ->addDays($this->subscription->days + 1)
            ->subMinute();

        $this->user->save();
        if ($existingSubscriptionId != $this->subscription->id || $candidateAdTypes != $existingAdtypes) {
            event(new SubscriptionWasSwaped($this->subscription, $this->user));
        }
    }
}
