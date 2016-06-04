<li class="item">
@if ($notification->action == 'create')
  <i class="fa fa-cogs bg-aqua"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
    {!! trans('backend.notification.user_create_ad_with_adtype', ['user' => $actorName, 'adtype' => "<a href=" . route('zxadmin.adtype.edit', $notification->notified_id) . ">" . $notification->notified_name . "</a>"]) !!}
     </h3>
  </div>
@elseif ($notification->action == 'purchase')
  <i class="fa fa-cogs bg-green"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
    {!! trans('backend.notification.user_purchase_ad_with_adtype', ['user' => $actorName, 'number' => $notification->data, 'adtype' => "<a href=" . route('zxadmin.adtype.edit', $notification->notified_id) . ">" . $notification->notified_name . "</a>"]) !!}
    </h3>
  </div>
@endif
</li>
