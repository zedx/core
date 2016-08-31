@extends('backend::layout')
@section('page_header', trans("backend.widget.widget"))
@section('page_description', trans("backend.widget.list"))
@section('page_right')
<a href="{{ route('zxadmin.widget.index') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans('backend.widget.list') !!}</span></a>
<span class="dropdown">
  <button class="btn btn-success dropdown-toggle" type="button" id="addWidgetType" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <i class="fa fa-plus"></i> {!! trans('backend.widget.add') !!}
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu pull-right" aria-labelledby="addWidgetType">
    <li><a href="{{ route('zxadmin.widget.addWithTab', ['frontend', 'search']) }}">{!! trans("backend.widget.type.frontend") !!}</a></li>
    <li><a href="{{ route('zxadmin.widget.addWithTab', ['backend', 'search']) }}">{!! trans("backend.widget.type.backend") !!}</a></li>
  </ul>
</span>
@endsection

@section('content')
@include('backend::widget._partials.existingList', ['widgets' => $widgetsFrontend, 'widgetsHeaderTitle' => trans('backend.widget.frontend_widgets_list')])
@include('backend::widget._partials.existingList', ['widgets' => $widgetsBackend, 'widgetsHeaderTitle' => trans('backend.widget.backend_widgets_list')])
@endsection
