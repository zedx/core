@if ($notification->action == 'create')
  <i class="fa fa-cogs text-aqua"></i> {!! trans('backend.notification.user_create_ad_with_adtype', ['user' => $actorName, 'adtype' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@elseif ($notification->action == 'purchase')
  <i class="fa fa-shopping-cart text-green"></i> {!! trans('backend.notification.user_purchase_ad_with_adtype', ['user' => $actorName, 'number' => $notification->data, 'adtype' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@endif
