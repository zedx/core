<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">{{ $widgetsHeaderTitle }}</h3>
      </div><!-- /.box-header -->
      <div class="box-body no-padding">
        <div class="checkbox-auto-toggle">
          <table class="table table-striped">
            <tr>
              <th style="width: 10px"></th>
              <th>{!! trans("backend.widget.name") !!}</th>
              <th>{!! trans("backend.widget.description") !!}</th>
              <th>{!! trans("backend.widget.author") !!}</th>
              <th>{!! trans("backend.widget.version") !!}</th>
              <th style="width: 40px"></th>
            </tr>
            @foreach ($widgets as  $widget)
            @if($widget->author != 'Theme')
            <tr>
              <td><input type="checkbox" class="flat-red" /></td>
              <td>{{ $widget->title }}</td>
              <td>{{ $widget->description }}</td>
              <td>{{ $widget->author }}</td>
              <td>{{ $widget->version }}</td>
              <td>
                <button class="btn btn-xs btn-danger"><i class="fa fa-remove"></i> {!! trans("backend.widget.delete") !!}</button>
              </td>
            </tr>
            @endif
            @endforeach
          </table>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
</div>
