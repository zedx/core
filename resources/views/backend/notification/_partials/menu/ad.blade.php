@if ($notification->action == 'create')
  <i class="fa fa-paper-plane-o text-aqua"></i> {!! trans('backend.notification.user_create_ad', ['user' => $actorName, 'ad' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@elseif ($notification->action == 'hold')
  <i class="fa fa-paper-plane-o text-aqua"></i> {!! trans('backend.notification.user_hold_ad', ['user' => $actorName, 'ad' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@elseif ($notification->action == 'expire')
  <i class="fa fa-paper-plane-o text-yellow"></i> {!! trans('backend.notification.user_expire_ad', ['user' => $actorName, 'ad' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@elseif ($notification->action == 'banish')
  <i class="fa fa-paper-plane-o text-orange"></i> {!! trans('backend.notification.user_banish_ad', ['user' => $actorName, 'ad' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@elseif ($notification->action == 'validate')
  <i class="fa fa-paper-plane-o text-green"></i> {!! trans('backend.notification.user_validate_ad', ['user' => $actorName, 'ad' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@elseif ($notification->action == 'renewRequest')
  <i class="fa fa-paper-plane-o text-blue"></i> {!! trans('backend.notification.user_renew_ad', ['user' => $actorName, 'ad' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@elseif ($notification->action == 'update')
  <i class="fa fa-paper-plane-o text-blue"></i> {!! trans('backend.notification.user_update_ad', ['user' => $actorName, 'ad' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@elseif ($notification->action == 'delete')
  <i class="fa fa-paper-plane-o text-red"></i> {!! trans('backend.notification.user_delete_ad', ['user' => $actorName, 'ad' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@endif
