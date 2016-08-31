@extends('backend::layout')
@section('page_header', trans("backend.widget.widget"))
@section('page_description', trans("backend.widget.add_new_widget") . ' <b>[' . trans("backend.widget.type." . $widgetType) . ']</b>')
@section('page_right')
<a href="{{ route('zxadmin.widget.index') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{{ trans("backend.widget.list") }}</span></a>
<span class="dropdown">
  <button class="btn btn-success dropdown-toggle" type="button" id="addWidgetType" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <i class="fa fa-plus"></i> {!! trans('Ajouter') !!}
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu pull-right" aria-labelledby="addWidgetType">
    <li><a href="{{ route('zxadmin.widget.addWithTab', ['frontend', 'search']) }}"> {!! trans("backend.widget.type.frontend") !!}</a></li>
    <li><a href="{{ route('zxadmin.widget.addWithTab', ['backend', 'search']) }}"> {!! trans("backend.widget.type.backend") !!}</a></li>
  </ul>
</span>
@endsection

@section('content')
<div class="row">

  <div class="col-md-12">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li @if (isset($tab) && $tab == 'search') class="active" @endif><a href="{{ route('zxadmin.widget.addWithTab', [$widgetType, 'search']) }}"><i class="fa fa-search"></i><span class="hidden-sm hidden-xs"> {{ trans("backend.widget.search") }}</span></a></li>
        <!--
        <li @if (isset($tab) && $tab == 'upload') class="active" @endif><a href="{{ route('zxadmin.widget.addWithTab', [$widgetType, 'upload']) }}"><i class="fa fa-upload"></i><span class="hidden-sm hidden-xs"> Charger</span></a></li>
        -->
        <li @if (isset($tab) && $tab == 'api' && Request::get('sort') == 'popular') class="active" @endif><a href="{{ route('zxadmin.widget.addWithTab', [$widgetType, 'api']) . '?sort=popular' }}"><i class="fa fa-fire"></i><span class="hidden-sm hidden-xs"> {{ trans("backend.widget.most_popular") }}</span></a></li>
        <li @if (isset($tab) && $tab == 'api' && Request::get('sort') == 'newest') class="active" @endif><a href="{{ route('zxadmin.widget.addWithTab', [$widgetType, 'api']) . '?sort=newest' }}"><i class="fa fa-bullhorn"></i><span class="hidden-sm hidden-xs"> {{ trans("backend.widget.most_recent") }}</span></a></li>
        <li @if (isset($tab) && $tab == 'api' && Request::get('author') == 'zedx') class="active" @endif><a href="{{ route('zxadmin.widget.addWithTab', [$widgetType, 'api']) . '?author=zedx' }}">ZEDx</a></li>
      </ul>
      <div class="tab-content">
        @if ($tab == 'search')
          @include('backend::widget._partials.search')
        @elseif ($tab == 'upload')
          @include('backend::widget._partials.upload')
        @else
          @include('backend::widget._partials.externalList')
        @endif
      </div>
    </div>
  </div><!-- /.col -->
</div>
@endsection
