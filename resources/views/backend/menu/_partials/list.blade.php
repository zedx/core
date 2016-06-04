<div class="row">
  <div class="col-md-12">
    <div class="nav-tabs-custom">
    <!-- Tabs within a box -->
      <ul class="nav nav-tabs pull-s">
        @foreach ($groups as $group => $name)
        <li @if ($groupName == $group) class="active" @endif><a href="{{ route('zxadmin.menu.group', $group) }}">{{ $name }}</a></li>
        @endforeach
      </ul>
      <div class="tab-content no-padding">

        <div class="box box-solid">
          <div class="box-body">
            <div id="nestableList" class="dd" data-url = "{{ route('zxadmin.menu.index') }}">
              <ol class="dd-list">
              @foreach ($menus as $menu)
                {!! renderNode($menu, "menu") !!}
              @endforeach
              </ol>
            </div>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div>
    </div>
  </div>
</div>
