<li class="item">
  <i class="fa fa-heart text-red"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</span>
    <h3 class="timeline-header no-border">
    {!! trans('backend.notification.website_creation', ["ZEDx" => '<a href="http://www.zedx.io/">ZEDx</a>']) !!}
    </h3>
  </div>
</li>
<li class="item">
  <i class="fa fa-clock-o bg-grey"></i>
</li>
