@if (!Updater::isLatest())
{{--*/ $updatesList = Updater::getUpdatesList(); /*--}}
<li class="dropdown notifications-menu">
  <!-- Menu toggle button -->
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-refresh"></i>
      <span class="label label-danger">{{ !empty($updatesList['core']) + !empty($updatesList['theme']) + !empty($updatesList['module']) + !empty($updatesList['widget']) }}</span>
  </a>
  <ul class="dropdown-menu">
    <li class="header"><b>{!! trans('backend.update.updates_list') !!}</b></li>
    <li>
      <!-- Inner Menu: contains the notifications -->
      <ul class="menu">
        @foreach ($updatesList as $type => $list)
          @if ($type == "core" && !empty($list))
          <li class="new">
            <a href="{{ route('zxadmin.update.index', ['core']) }}">
              <i class="fa fa-heart text-red"></i> {!! trans('backend.update.update_available', ['package' => 'ZEDx', 'version' => Updater::getLatest()->version]) !!}</small>
              <br>
              <small class="time"><i class="fa fa-clock-o"></i> {{ Carbon\Carbon::parse(head($list)['date'])->diffForHumans() }}</small>
            </a>
          </li>
          @elseif (!empty($list))
          <li class="new">
            <a href="{{ route('zxadmin.update.index', [$type]) }}">
              <i class="fa fa-heart text-red"></i> {{ count($list) }} {{ $type }} à mettre à jour</small>
              <br>
              <small class="time"><i class="fa fa-clock-o"></i> {{ Carbon\Carbon::parse(head($list)['date'])->diffForHumans() }}</small>
            </a>
          </li>
          @endif
        @endforeach
      </ul>
    </li>
  </ul>
</li>
@endif
