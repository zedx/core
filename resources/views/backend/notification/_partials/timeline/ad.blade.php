<li class="item">
@if ($notification->action == 'create')
  <i class="fa fa-paper-plane-o bg-aqua"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
    {!! trans('backend.notification.user_create_ad', ['user' => $actorName, 'ad' => "<a href='" . route('ad.preview', $notification->notified_id) . "'>" . $notification->notified_name . "</a>"]) !!}
    </h3>
  </div>
@elseif ($notification->action == 'hold')
  <i class="fa fa-paper-plane-o bg-aqua"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
    {!! trans('backend.notification.user_hold_ad', ['user' => $actorName, 'ad' => "<a href='" . route('ad.preview', $notification->notified_id) . "'>" . $notification->notified_name . "</a>"]) !!}
    </h3>
  </div>
@elseif ($notification->action == 'expire')
  <i class="fa fa-paper-plane-o bg-yellow"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
    {!! trans('backend.notification.user_expire_ad', ['user' => $actorName, 'ad' => "<a href='" . route('ad.preview', $notification->notified_id) . "'>" . $notification->notified_name . "</a>"]) !!}
    </h3>
  </div>
@elseif ($notification->action == 'banish')
  <i class="fa fa-paper-plane-o bg-orange"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
    {!! trans('backend.notification.user_banish_ad', ['user' => $actorName, 'ad' => "<a href='" . route('ad.preview', $notification->notified_id) . "'>" . $notification->notified_name . "</a>"]) !!}
    </h3>
  </div>
@elseif ($notification->action == 'validate')
  <i class="fa fa-paper-plane-o bg-green"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
    {!! trans('backend.notification.user_validate_ad', ['user' => $actorName, 'ad' => "<a href='" . route('ad.preview', $notification->notified_id) . "'>" . $notification->notified_name . "</a>"]) !!}
    </h3>
  </div>
@elseif ($notification->action == 'update')
  <i class="fa fa-paper-plane-o bg-blue"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
    {!! trans('backend.notification.user_update_ad', ['user' => $actorName, 'ad' => "<a href='" . route('ad.preview', $notification->notified_id) . "'>" . $notification->notified_name . "</a>"]) !!}
    </h3>
  </div>
@elseif ($notification->action == 'renewRequest')
  <i class="fa fa-paper-plane-o bg-blue"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
    {!! trans('backend.notification.user_renew_ad', ['user' => $actorName, 'ad' => "<a href='" . route('ad.preview', $notification->notified_id) . "'>" . $notification->notified_name . "</a>"]) !!}
    </h3>
  </div>
@elseif ($notification->action == 'delete')
  <i class="fa fa-paper-plane-o bg-red"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
    {!! trans('backend.notification.user_delete_ad', ['user' => $actorName, 'ad' => "<a href='" . route('ad.preview', $notification->notified_id) . "'>" . $notification->notified_name . "</a>"]) !!}
    </h3>
  </div>
@endif
</li>
