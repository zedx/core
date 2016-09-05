 <div class="box box-solid">
  <div class="box-body no-padding">
    <div class="checkbox-auto-toggle">
    @if (count($widgets))
      <table class="table table-striped">
        <tr>
          <th></th>
          <th class="col-md-2">{!! trans("Nom") !!}</th>
          <th class="col-md-1">{!! trans("Version") !!}</th>
          <th class="col-md-6">{!! trans("Description") !!}</th>
          <th class="col-md-2"></th>
        </tr>
        @foreach ($widgets as $widget)
        <tr>
          <td><input type="checkbox" class="flat-red" /></td>
          <td>{{ $widget->name }}</td>
          <td>{{ $widget->version }}</td>
          <td>{{ $widget->description }}</td>
          <td class="pull-right">
          @if (Widgets::noType()->exists($widget->namespace.'\\'.$widget->author.'\\'.$widget->name))
          <button class="btn btn-block btn-xs btn-success" disabled="true"><i class="fa fa-check"></i> Installed</button>
          @else
          <button class="zedx-install-component btn btn-block btn-xs btn-primary" data-url="{{ route('zxadmin.widget.download', [$widget->namespace, $widget->author, $widget->name]) }}" data-component-type="widget" data-component-name="{{ $widget->name }}"><i class="fa fa-download"></i> Installer</button>
          @endif
          </td>
        </tr>
        @endforeach
      </table>
      @elseif ($tab != "search")
        <br />
          <p class="text-center">Aucun widget n'est disponible pour l'instant</p>
        <br />
      @endif
    </div><!-- /.mail-box-messages -->
  </div><!-- /.box-body -->
  @if (count($widgets))
  <div class="box-footer no-padding">
    <div class="pull-right">
      {!! with(new ZEDx\Utils\Pagination($widgets->appends(['q' => Request::get('q')])))->render() !!}
    </div><!-- /.pull-right -->
  </div>
  @endif

</div><!-- /. box -->
