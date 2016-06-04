@if ($notification->action == 'create')
  <i class="fa fa-user text-aqua"></i> {!! trans('backend.notification.create_user', ['admin' => $actorName, 'user' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@elseif ($notification->action == 'update')
  <i class="fa fa-user text-blue"></i> {!! trans('backend.notification.update_user', ['admin' => $actorName, 'user' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@elseif ($notification->action == 'delete')
  <i class="fa fa-user text-red"></i> {!! trans('backend.notification.delete_user', ['admin' => $actorName, 'user' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@endif
