<!-- Modal Dialog -->
<div class="modal fade" id="backendWidgetSetting{{ $widgetnode->id }}" role="dialog" aria-labelledby="backendWidgetSetting{{ $widgetnode->id }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4>{{ $widgetnode->title }}</h4>
      </div>
      {!! Form::open(['method' => 'PATCH', 'url' => route('zxadmin.dashboard.update', $widgetnode)]) !!}
      <div class="modal-body">
        @widgetSetting($widgetnode->namespace, $widgetnode->config, route('zxadmin.dashboard.update', $widgetnode))
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
