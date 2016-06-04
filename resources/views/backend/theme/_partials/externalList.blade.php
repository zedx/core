 <div class="box box-solid">
  <div class="box-body no-padding">
    <div class="checkbox-auto-toggle">
    @if (count($themes))
      <table class="table table-striped">
        <tr>
          <th></th>
          <th class="col-md-2">{!! trans("Nom") !!}</th>
          <th class="col-md-1">{!! trans("Version") !!}</th>
          <th class="col-md-6">{!! trans("Description") !!}</th>
          <th class="col-md-2"></th>
        </tr>
        @foreach ($themes as $theme)
        <tr>
          <td><input type="checkbox" class="flat-red" /></td>
          <td>{{ $theme->name }}</td>
          <td>{{ $theme->version }}</td>
          <td>{{ $theme->description }}</td>
          <td class="pull-right">
          @if (Themes::has($theme->name))
          <button class="btn btn-block btn-xs btn-success" disabled="true"><i class="fa fa-check"></i> Installed</button>
          @else
          <button class="zedx-install-component btn btn-block btn-xs btn-primary" data-url="{{ route('zxadmin.theme.download', [$theme->name]) }}" data-component-type="theme" data-component-name="{{ $theme->name }}"><i class="fa fa-download"></i> Installer</button>
          @endif
          </td>
        </tr>
        @endforeach
      </table>
      @elseif ($tab != "search")
        <br />
          <p class="text-center">Aucun theme n'est disponible pour l'instant</p>
        <br />
      @endif
    </div><!-- /.mail-box-messages -->
  </div><!-- /.box-body -->
  @if (count($themes))
  <div class="box-footer no-padding">
    <div class="pull-right">
      {!! with(new ZEDx\Utils\Pagination($themes->appends(['q' => Request::get('q')])))->render() !!}
    </div><!-- /.pull-right -->
  </div>
  @endif

</div><!-- /. box -->
