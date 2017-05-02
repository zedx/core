<?php

namespace ZEDx\Listeners;

use Carbon\Carbon;
use ZEDx\Jobs\UpdateCache;
use ZEDx\Mailers\AdMail;
use ZEDx\Models\Notification;
use ZEDx\Models\Role;
use Session;

class AdEventListener
{
    /**
     * Handle ad create events.
     */
    public function onAdCreate($event)
    {
        $is_visible = in_array(setting('tell_me_new_ads'), [2, 3]);
        $is_admin_mail = in_array(setting('tell_me_new_ads'), [1, 3]);

        $this->notification($event, 'create', $is_visible);
        $this->sendMail($event, 'created', false, $is_admin_mail);
    }

    /**
     * Handle ad wait events.
     */
    public function onAdHold($event)
    {
        $this->notification($event, 'hold');

        dispatch(new UpdateCache($event->ad));
    }

    /**
     * Handle ad expire events.
     */
    public function onAdExpire($event)
    {
        $event->ad->expired_at = Carbon::now();
        $event->ad->save();

        $is_user_mail = in_array(setting('tell_client_ad_expired'), [1, 3]);

        $this->notification($event, 'expire');
        $this->sendMail($event, 'expired', $is_user_mail, false);

        dispatch(new UpdateCache($event->ad));
    }

    /**
     * Handle ad banish events.
     */
    public function onAdBanish($event)
    {
        $is_user_mail = in_array(setting('tell_client_ad_refused'), [1, 3]);

        $this->notification($event, 'banish');
        $this->sendMail($event, 'refused', $is_user_mail, false);

        dispatch(new UpdateCache($event->ad));
    }

    /**
     * Handle ad Renew Request events.
     */
    public function onAdRenewRequest($event)
    {
        $is_visible = in_array(setting('tell_me_renew_ads'), [2, 3]);
        $is_admin_mail = in_array(setting('tell_me_renew_ads'), [1, 3]);

        $this->notification($event, 'renewRequest', $is_visible);
        $this->sendMail($event, 'renewRequest', false, $is_admin_mail);

        dispatch(new UpdateCache($event->ad));
    }

    /**
     * Handle ad validate events.
     */
    public function onAdValidate($event)
    {
        $event->ad->published_at = Carbon::now();
        $nbrDays = $event->ad->adtype->nbr_days;
        $event->ad->expired_at = $nbrDays >= 9999 ? null : Carbon::now()->addDays($nbrDays + 1);

        $event->ad->save();

        $is_user_mail = in_array(setting('tell_client_ad_accepted'), [1, 3]);

        $this->notification($event, 'validate');
        $this->sendMail($event, 'validated', $is_user_mail, false);

        dispatch(new UpdateCache($event->ad));
    }

    /**
     * Handle ad update events.
     */
    public function onAdUpdate($event)
    {
        $is_visible = in_array(setting('tell_me_edit_ads'), [2, 3]);
        $is_admin_mail = in_array(setting('tell_me_edit_ads'), [1, 3]);

        $this->notification($event, 'update', $is_visible);
        $this->sendMail($event, 'updated', false, $is_admin_mail);
        dispatch(new UpdateCache($event->ad));
    }

    /**
     * Handle ad update events.
     */
    public function onAdDisplay($event)
    {
        $viewed = Session::get('viewed_ads', []);
        $adId = $event->ad->id;

        if (!in_array($adId, $viewed)) {
            $event->ad->increment('views');
            Session::push('viewed_ads', $adId);
        }
    }

    /**
     * Handle ad delete events.
     */
    public function onAdDelete($event)
    {
        $is_user_mail = in_array(setting('tell_client_ad_deleted'), [1, 3]);

        $this->notification($event, 'delete');
        $this->sendMail($event, 'deleted', $is_user_mail, false);

        dispatch(new UpdateCache($event->ad, true));
    }

    protected function sendMail($event, $action, $is_user_mail, $is_admin_mail)
    {
        $mailer = new AdMail();

        $role = Role::whereName('root')->firstOrFail();
        $admin = $role->admins->first();

        $data = ['ad' => $event->ad];

        if ($is_admin_mail) {
            $mailer->admin()->$action($admin, $data);
        }

        if ($is_user_mail) {
            $mailer->user()->$action($event->ad->user, $data);
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
            'notified_name' => $event->ad->content->title,
            'notified_id'   => $event->ad->id,
            'action'        => $action,
            'type'          => 'ad',
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
        $class = "ZEDx\Listeners\AdEventListener";

        $events->listen('ZEDx\Events\Ad\AdWasCreated', $class.'@onAdCreate');
        $events->listen('ZEDx\Events\Ad\AdWasHold', $class.'@onAdHold');
        $events->listen('ZEDx\Events\Ad\AdWasExpired', $class.'@onAdExpire');
        $events->listen('ZEDx\Events\Ad\AdWasBanned', $class.'@onAdBanish');
        $events->listen('ZEDx\Events\Ad\AdWasValidated', $class.'@onAdValidate');
        $events->listen('ZEDx\Events\Ad\AdWasUpdated', $class.'@onAdUpdate');
        $events->listen('ZEDx\Events\Ad\AdWasDeleted', $class.'@onAdDelete');
        $events->listen('ZEDx\Events\Ad\AdRenewRequested', $class.'@onAdRenewRequest');
        $events->listen('ZEDx\Events\Ad\AdWillBeDisplayed', $class.'@onAdDisplay');
    }
}
