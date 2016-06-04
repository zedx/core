 <div class="box box-solid">
  <div class="box-body no-padding">
    <div class="checkbox-auto-toggle">
    @if (count($modules))
      <table class="table table-striped">
        <tr>
          <th></th>
          <th class="col-md-2">{!! trans("Nom") !!}</th>
          <th class="col-md-1">{!! trans("Version") !!}</th>
          <th class="col-md-6">{!! trans("Description") !!}</th>
          <th class="col-md-2"></th>
        </tr>
        @foreach ($modules as $module)
        <tr>
          <td><input type="checkbox" class="flat-red" /></td>
          <td>{{ $module->name }}</td>
          <td>{{ $module->version }}</td>
          <td>{{ $module->description }}</td>
          <td class="pull-right">
          @if (Modules::has($module->name))
          <button class="btn btn-block btn-xs btn-success" disabled="true"><i class="fa fa-check"></i> Installed</button>
          @else
          <button class="zedx-install-component btn btn-block btn-xs btn-primary" data-url="{{ route('zxadmin.module.download', [$module->name]) }}" data-component-type="module" data-component-name="{{ $module->name }}"><i class="fa fa-download"></i> Installer</button>
          @endif
          </td>
        </tr>
        @endforeach
      </table>
      @elseif ($tab != "search")
        <br />
          <p class="text-center">Aucun module n'est disponible pour l'instant</p>
        <br />
      @endif
    </div><!-- /.mail-box-messages -->
  </div><!-- /.box-body -->
  @if (count($modules))
  <div class="box-footer no-padding">
    <div class="pull-right">
      {!! with(new ZEDx\Utils\Pagination($modules->appends(['q' => Request::get('q')])))->render() !!}
    </div><!-- /.pull-right -->
  </div>
  @endif

</div><!-- /. box -->
