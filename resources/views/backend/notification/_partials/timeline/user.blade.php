<li class="item">
@if ($notification->action == 'create')
  <i class="fa fa-user bg-aqua"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
    {!! trans('backend.notification.create_user', ['admin' => $actorName, 'user' => "<a href=" . route('zxadmin.user.edit', $notification->notified_id) . ">" . $notification->notified_name . "</a>"]) !!}
     </h3>
  </div>
@elseif ($notification->action == 'update')
  <i class="fa fa-user bg-blue"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
    {!! trans('backend.notification.update_user', ['admin' => $actorName, 'user' => "<a href=" . route('zxadmin.user.edit', $notification->notified_id) . ">" . $notification->notified_name . "</a>"]) !!}
    </h3>
  </div>
@elseif ($notification->action == 'delete')
  <i class="fa fa-user bg-red"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
    {!! trans('backend.notification.delete_user', ['admin' => $actorName, 'user' => "<a href=" . route('zxadmin.user.edit', $notification->notified_id) . ">" . $notification->notified_name . "</a>"]) !!}
    </h3>
  </div>
@endif
</li>
