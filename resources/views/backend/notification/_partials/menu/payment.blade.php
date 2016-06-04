@if ($notification->action == 'received')
  <i class="fa fa-money text-green"></i>
  {!! trans('backend.notification.you_received_a_money', ['amount' => $notification->data, 'gateway' => $actorName, 'currency' => $currency, 'object' => "<small><b>" . $notification->notified_name . "</b></small>"]) !!}
@endif
