<li class="item">
@if ($notification->action == 'received')
  <i class="fa fa-money bg-green"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
    {!! trans('backend.notification.you_received_a_money', ['amount' => $notification->data, 'currency' => $currency, 'gateway' => $actorName, 'object' => $notification->notified_name]) !!}
    </h3>
  </div>
@endif
</li>
