@extends('backend::layout')
@section('page_header', trans('backend.dashboard.dashboard'))
@section('page_description', trans('backend.dashboard.admin_panel'))
@section('page_right')

@endsection

@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <div class="input-group">
        <form action="{{ route('zxadmin.dashboard.store') }}" id="widgetNodeForm" method="post">
        {!! csrf_field() !!}
         <select class="form-control select2" id="widgetsList">
          <option disabled selected>{!! trans('backend.dashboard.choose_widget') !!}</option>
          @foreach(Widgets::backend()->groupByAuthors()->all() as $author => $widgets)
            <optgroup label="{{ $author }}">
            @foreach($widgets as $widget)
              <option
                data-namespace="{{ $widget->getFullName() }}"
                data-title="{{ $widget->title }}"
                data-config='[]'
              >{{ $widget->title }} : {{ $widget->description }}</option>
            @endforeach
            </optgroup>
          @endforeach
         </select>
         <input type="hidden" id="widgetNamespace" name="namespace">
         <input type="hidden" id="widgetTitle" name="title">
         <input type="hidden" id="widgetConfig" name="config">
         <input type="hidden" name="size" value="6">
        </form>
        <span class="input-group-btn">
          <button id="addWidget" class="btn btn-success" type="button" style=""><i class="fa fa-plus"></i> {!! trans('backend.dashboard.add_widget') !!}</button>
          <a href="{{ route('zxadmin.widget.addWithTab', ['backend', 'search']) }}" class="btn btn-primary" type="button" style=""><i class="fa fa-upload"></i> {!! trans('backend.dashboard.upload_new_widget') !!}</a>
        </span>

      </div><!-- /input-group -->
    </div>
  </div>
</div>
<div class="row">
  @foreach ($dashboardWidgets as $dashboardWidget)
  <section id="widget_{{ $dashboardWidget->id }}" class="col-md-{{ $dashboardWidget->size }}" data-size="{{ $dashboardWidget->size }}" data-url="{{ route('zxadmin.dashboard.update', $dashboardWidget->id) }}" >
    <!-- TABLE: LATEST ORDERS -->
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-edit"></i> <a href="#" class="dashboard-widget-name" data-name="title" data-type="text" >{{ $dashboardWidget->title }}</a></h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool"><i class="fa fa-resize"></i></button>
          <button type="button" class="btn btn-box-tool"><i class="fa fa-wrench"></i></button>
          <form method="POST" action="{{ route('zxadmin.dashboard.destroy', $dashboardWidget->id) }}" accept-charset="UTF-8" style="display:inline">
            <input name="_method" type="hidden" value="DELETE">
            {{ csrf_field() }}
            <button class="btn btn-box-tool" type="button" data-toggle="modal" data-target="#confirmWidgetAction" data-title="{{ $dashboardWidget->title }}" data-message="{!! trans('backend.dashboard.delete_widget_confirmation') !!}"><i class="fa fa-times"></i></button>
          </form>
        </div>
      </div>
      <div class="box-body">
        @widget($dashboardWidget->namespace, $dashboardWidget->config)
      </div>
      <div class="box-footer hidden-xs hidden-sm">
        <button type="button" class="btn btn-default" data-resize-widget = "minus"><i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-default pull-right" data-resize-widget = "plus"><i class="fa fa-plus"></i></button>
      </div>
    </div>
  </section>
  @endforeach
</div>
@include('backend::widget.modals.delete')
@endsection
