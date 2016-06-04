<?php

namespace ZEDx\Listeners;

use ZEDx\Jobs\UpdateCache;
use ZEDx\Mailers\SubscriptionMail;
use ZEDx\Models\Notification;
use ZEDx\Models\Role;

class SubscriptionEventListener
{
    protected $user;
    protected $actorName;
    protected $actorId;
    protected $actorRole;

    /**
     * Handle subscription create events.
     */
    public function onSubscriptionCreate($event)
    {
        dispatch(new UpdateCache($event->subscription));
    }

    /**
     * Handle subscription delete events.
     */
    public function onSubscriptionDelete($event)
    {
        dispatch(new UpdateCache($event->subscription, true));
    }

    /**
     * Handle subscription update events.
     */
    public function onSubscriptionUpdate($event)
    {
        dispatch(new UpdateCache($event->subscription));
    }

    /**
     * Handle subscription swap events.
     */
    public function onSubscriptionSwap($event)
    {
        $is_user_mail = in_array(setting('tell_client_new_subscr'), [1, 3]);

        $this->user = $event->user;
        $this->actorName = $this->user->name;
        $this->actorId = $this->user->id;
        $this->actorRole = $this->user->role()->first()->name;

        $this->notification($event, 'swap');
        $this->sendMail($event, 'activated', $is_user_mail, false);
    }

    public function onSubscriptionPurchase($event)
    {
        $is_visible = in_array(setting('tell_me_new_payment_subscr'), [2, 3]);
        $is_admin_mail = in_array(setting('tell_me_new_payment_subscr'), [1, 3]);

        $this->user = $event->order->user;
        $this->actorName = $this->user->name;
        $this->actorId = $this->user->id;
        $this->actorRole = $this->user->role()->first()->name;

        $this->notification($event, 'purchase', $is_visible);
        $this->sendMail($event, 'purchased', false, $is_admin_mail);
    }

    protected function sendMail($event, $action, $is_user_mail, $is_admin_mail)
    {
        $mailer = new SubscriptionMail();

        $role = Role::whereName('root')->firstOrFail();
        $admin = $role->admins->first();

        $data = [
            'user'         => $this->user,
            'subscription' => $event->subscription,
        ];

        if ($is_admin_mail) {
            $mailer->admin()->$action($admin, $data);
        }

        if ($is_user_mail) {
            $mailer->user()->$action($this->user, $data);
        }
    }

    protected function notification($event, $action, $is_visible = false)
    {
        Notification::create([
            'actor_name'    => $this->actorName,
            'actor_id'      => $this->actorId,
            'actor_role'    => $this->actorRole,
            'notified_name' => $event->subscription->title,
            'notified_id'   => $event->subscription->id,
            'action'        => $action,
            'type'          => 'subscription',
            'is_visible'    => $is_visible,
        ]);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Illuminate\Events\Dispatcher $events
     *
     * @return array
     */
    public function subscribe($events)
    {
        $class = "ZEDx\Listeners\SubscriptionEventListener";

        $events->listen('ZEDx\Events\Subscription\SubscriptionWasPurchased', $class.'@onSubscriptionPurchase');
        $events->listen('ZEDx\Events\Subscription\SubscriptionWasSwaped', $class.'@onSubscriptionSwap');

        $events->listen('ZEDx\Events\Subscription\SubscriptionWasCreated', $class.'@onSubscriptionCreate');
        $events->listen('ZEDx\Events\Subscription\SubscriptionWasDeleted', $class.'@onSubscriptionDelete');
        $events->listen('ZEDx\Events\Subscription\SubscriptionWasUpdated', $class.'@onSubscriptionUpdate');
    }
}
