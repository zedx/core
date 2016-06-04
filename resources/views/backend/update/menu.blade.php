@if (!Updater::isLatest())
<li class="dropdown notifications-menu">
  <!-- Menu toggle button -->
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-refresh"></i>
      <span class="label label-danger">1</span>
  </a>
  <ul class="dropdown-menu">
    <li class="header"><b>{!! trans('backend.update.updates_list') !!}</b></li>
    <li>
      <!-- Inner Menu: contains the notifications -->
      <ul class="menu">
        <li class="new"><!-- start notification -->
          <a href="{{ route('zxadmin.update.show', ['zedx']) }}">
            <i class="fa fa-heart text-red"></i> {!! trans('backend.update.update_available', ['package' => 'ZEDx', 'version' => Updater::getLatest()->version]) !!}</small>
          <br>
          <small class="time"><i class="fa fa-clock-o"></i> {{ Carbon\Carbon::parse(Updater::getLatest()->date)->diffForHumans() }}</small>
        </a>
        </li><!-- end notification -->
      </ul>
    </li>
  </ul>
</li>
@endif
