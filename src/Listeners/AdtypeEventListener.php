<?php

namespace ZEDx\Listeners;

use ZEDx\Jobs\UpdateCache;
use ZEDx\Mailers\AdtypeMail;
use ZEDx\Models\Notification;
use ZEDx\Models\Role;

class AdtypeEventListener
{
    /**
     * Handle adtype purchase events.
     */
    public function onAdtypePurchase($event)
    {
        $is_visible = in_array(setting('tell_me_payment_ads'), [2, 3]);
        $is_admin_mail = in_array(setting('tell_me_payment_ads'), [1, 3]);
        $is_user_mail = in_array(setting('tell_client_ad_type_changed'), [1, 3]);

        $this->notification($event, 'purchase', $is_visible);
        $this->sendMail($event, 'purchased', $is_user_mail, $is_admin_mail);
    }

    /**
     * Handle adtype creating events.
     */
    public function onAdtypeCreating($event)
    {
    }

    /**
     * Handle adtype created events.
     */
    public function onAdtypeCreated($event)
    {
    }

    /**
     * Handle adtype updating events.
     */
    public function onAdtypeUpdating($event)
    {
    }

    /**
     * Handle adtype updated events.
     */
    public function onAdtypeUpdated($event)
    {
        //dispatch(new UpdateCache($event->adtype));
    }

    /**
     * Handle adtype deleted events.
     */
    public function onAdtypeDeleted($event)
    {
        //dispatch(new UpdateCache($event->adtype, true));
    }

    /**
     * Handle adtype deleting events.
     */
    public function onAdtypeDeleting($event)
    {
    }

    protected function sendMail($event, $action, $is_user_mail, $is_admin_mail)
    {
        $mailer = new AdtypeMail();

        $role = Role::whereName('root')->firstOrFail();
        $admin = $role->admins->first();
        $user = $event->order->user;
        $data = [
            'user'   => $user,
            'adtype' => $event->adtype,
            'number' => $event->order->quantity,
        ];

        if ($is_admin_mail) {
            $mailer->admin()->$action($admin, $data);
        }

        if ($is_user_mail) {
            $mailer->user()->$action($user, $data);
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
            'notified_name' => $event->adtype->title,
            'notified_id'   => $event->adtype->id,
            'data'          => $event->order->quantity,
            'action'        => $action,
            'type'          => 'adtype',
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
        $class = "ZEDx\Listeners\AdtypeEventListener";

        $events->listen('ZEDx\Events\Adtype\AdtypeWasPurchased', $class.'@onAdtypePurchase');
        $events->listen('ZEDx\Events\Adtype\AdtypeWillBeCreated', $class.'@onAdtypeCreating');
        $events->listen('ZEDx\Events\Adtype\AdtypeWasCreated', $class.'@onAdtypeCreated');
        $events->listen('ZEDx\Events\Adtype\AdtypeWillBeUpdated', $class.'@onAdtypeUpdating');
        $events->listen('ZEDx\Events\Adtype\AdtypeWasUpdated', $class.'@onAdtypeUpdated');
        $events->listen('ZEDx\Events\Adtype\AdtypeWasDeleted', $class.'@onAdtypeDeleted');
        $events->listen('ZEDx\Events\Adtype\AdtypeWillBeDeleted', $class.'@onAdtypeDeleting');
    }
}
