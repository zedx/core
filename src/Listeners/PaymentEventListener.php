<?php

namespace ZEDx\Listeners;

use ZEDx\Mailers\PaymentMail;
use ZEDx\Models\Notification;
use ZEDx\Models\Role;

class PaymentEventListener
{
    /**
     * Handle adtype create events.
     */
    public function onPaymentReceive($event)
    {
        $is_visible = in_array(setting('tell_me_payment_received'), [2, 3]);
        $is_admin_mail = in_array(setting('tell_me_payment_received'), [1, 3]);

        $this->notification($event, 'received', $is_visible);
        $this->sendMail($event, 'received', false, $is_admin_mail);
    }

    protected function sendMail($event, $action, $is_user_mail, $is_admin_mail)
    {
        $mailer = new PaymentMail();

        $role = Role::whereName('root')->firstOrFail();
        $admin = $role->admins->first();

        $data = [
            'amount'   => $event->order->amount,
            'currency' => setting('currency'),
            'gateway'  => $event->actor,
        ];

        if ($is_admin_mail) {
            $mailer->admin()->$action($admin, $data);
        }
    }

    protected function notification($event, $action, $is_visible = false)
    {
        $actorName = is_string($event->actor) ? $event->actor : $event->actor->name;
        $actorId = is_string($event->actor) ? null : $event->actor->id;
        $actorRole = is_string($event->actor) ? 'system' : $event->actor->role->name;

        Notification::create([
            'actor_name'    => $actorName,
            'actor_id'      => $actorId,
            'actor_role'    => $actorRole,
            'notified_name' => $event->order->name,
            'data'          => $event->order->amount,
            'action'        => $action,
            'type'          => 'payment',
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
        $class = "ZEDx\Listeners\PaymentEventListener";

        $events->listen('ZEDx\Events\Payment\PaymentWasReceived', $class.'@onPaymentReceive');
    }
}
