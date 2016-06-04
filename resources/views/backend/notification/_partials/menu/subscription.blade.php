@if ($notification->action == 'swap')
  <i class="fa fa-shopping-cart text-green"></i> {!! trans('backend.notification.user_subscribe', ['user' => $actorName, 'subscription' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@elseif ($notification->action == 'purchase')
  <i class="fa fa-shopping-cart text-green"></i> {!! trans('backend.notification.user_purchase_subscription', ['user' => $actorName, 'subscription' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@endif
