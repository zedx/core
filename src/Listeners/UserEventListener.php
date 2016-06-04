<?php

namespace ZEDx\Listeners;

use ZEDx\Jobs\UpdateCache;
use ZEDx\Mailers\UserMail;
use ZEDx\Models\Notification;
use ZEDx\Models\Role;

class UserEventListener
{
    /**
     * Handle user create events.
     */
    public function onUserCreate($event)
    {
        $is_visible = in_array(setting('tell_me_new_user'), [2, 3]);
        $is_admin_mail = in_array(setting('tell_me_new_user'), [1, 3]);
        $is_user_mail = in_array(setting('new_user_welcome_message'), [1, 3]);

        $this->notification($event, 'create', $is_visible);
        $this->sendMail($event, 'created', $is_user_mail, $is_admin_mail);
        dispatch(new UpdateCache($event->user));
    }

    /**
     * Handle user update events.
     */
    public function onUserUpdate($event)
    {
        $this->notification($event, 'update');
        dispatch(new UpdateCache($event->user));
    }

    /**
     * Handle user delete events.
     */
    public function onUserDelete($event)
    {
        $this->notification($event, 'delete');
        dispatch(new UpdateCache($event->user, true));
    }

    protected function sendMail($event, $action, $is_user_mail, $is_admin_mail)
    {
        $mailer = new UserMail();

        $role = Role::whereName('root')->firstOrFail();
        $admin = $role->admins->first();

        $data = ['user' => $event->user];

        if ($is_admin_mail) {
            $mailer->admin()->$action($admin, $data);
        }

        if ($is_user_mail) {
            $mailer->user()->$action($event->user, $data);
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
            'notified_name' => $event->user->name,
            'notified_id'   => $event->user->id,
            'action'        => $action,
            'type'          => 'user',
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
        $class = "ZEDx\Listeners\UserEventListener";

        $events->listen('ZEDx\Events\User\UserWasCreated', $class.'@onUserCreate');
        $events->listen('ZEDx\Events\User\UserWasUpdated', $class.'@onUserUpdate');
        $events->listen('ZEDx\Events\User\UserWasDeleted', $class.'@onUserDelete');
    }
}
