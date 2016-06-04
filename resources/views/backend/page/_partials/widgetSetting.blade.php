<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"> <i class="fa fa-edit"></i>  <span class="page-widget-name" data-name="title" data-type="text" data-widget-id = "{{ $widgetnode->id }}" data-url="{{ route('zxadmin.widgetnode.update', [$page->id, $templateblock->identifier, $widgetnode->id]) }}">{{ $widgetnode->title }}</span></h3>

  </div><!-- /.box-header -->
  <div class="box-body">
    <div class="row">
      <div class="col-sm-12">
      @widgetSetting($widgetnode->namespace, $widgetnode->config, route('zxadmin.widgetnode.update', [$page->id, $templateblock->identifier, $widgetnode->id]))
      </div>
    </div>

  </div>


