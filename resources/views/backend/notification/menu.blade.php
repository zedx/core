<ul class="dropdown-menu">
  <li class="header"><b>{!! trans('backend.notification.notifications') !!}</b> <span class="pull-right"><a href="javascript:void(0)" id="mark-all-notifications" data-url="{{ route('zxadmin.notification.readall') }}">{!! trans('backend.notification.mark_all_as_read') !!}</a></span></li>
  <li>
    <!-- Inner Menu: contains the notifications -->
    <ul id="notifications-menu" class="menu">
    @foreach (ZEDx\Models\Notification::visible()->recents()->limit(7)->get() as $notification)
      <li class="{{ !$notification->is_read ? 'new' : '' }}"><!-- start notification -->
        <a href="#">
          @if ($notification->actor_role == 'root')
          {{--*/ $actorName = "<span class='text-red'><b>" . $notification->actor_name . "</b></span>" /*--}}
          @elseif ($notification->actor_role == 'user')
          {{--*/ $actorName = "<span><b>" . $notification->actor_name . "</b></span>" /*--}}
          @elseif ($notification->actor_role == 'system')
          {{--*/ $actorName = "<span class = 'text-green'><b>[ " . $notification->actor_name . " ]</b></span>" /*--}}
          @endif
          @include("backend::notification._partials.menu.{$notification->type}")
          <br />
          <small class="time"><i class="fa fa-clock-o"></i> {{ $notification->created_at->diffForHumans() }}</small>
        </a>
      </li><!-- end notification -->
    @endforeach
    </ul>
  </li>
  <li class="footer"><a href="{{ route('zxadmin.notification.index' )}}"><b>{!! trans('backend.notification.show_all') !!}</b></a></li>
</ul>
