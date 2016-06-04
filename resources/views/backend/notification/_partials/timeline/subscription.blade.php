<li class="item">
  @if ($notification->action == 'swap')
  <i class="fa fa-shopping-cart bg-green"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
      {!! trans('backend.notification.user_subscribe', ['user' => $actorName, 'subscription' => "<a href=" . route('zxadmin.subscription.edit', $notification->notified_id) . ">" . $notification->notified_name . "</a>"]) !!}
    </h3>
  </div>
  @elseif ($notification->action == 'purchase')
  <i class="fa fa-shopping-cart bg-green"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
      {!! trans('backend.notification.user_purchase_subscription', ['user' => $actorName, 'subscription' => "<a href=" . route('zxadmin.subscription.edit', $notification->notified_id) . ">" . $notification->notified_name . "</a>"]) !!}
    </h3>
  </div>
  @endif
</li>
