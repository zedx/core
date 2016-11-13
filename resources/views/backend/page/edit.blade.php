@extends('backend::layout')
@section('page_header', trans("backend.page.page"))
@section('page_description', $page->name)
@section('page_right')
<a href="{{ route('zxadmin.page.index') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans('backend.page.list') !!}</span></a>
<a href="{{ route('zxadmin.page.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.page.add') !!}</span></a>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary {{ Session::has('pageSettingEdited') ? '' : 'collapsed-box' }}">
      <div class="box-header with-border">
        <h3 class="box-title">{!! trans('backend.page.page_settings') !!}</h3>
        <div class="box-tools pull-right">
        @if(Session::has('pageSettingEdited'))
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        @else
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        @endif
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
      {!! Form::model($page, ['method' => 'PATCH', 'route' => ['zxadmin.page.update', $page->id]]) !!}
        @include('backend::page._form', array("submitButton" => trans('backend.page.edit')))
      {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    @include('backend::page._partials.template')
  </div><!-- /.col -->
  <div class="col-md-6">
    @include('backend::page._partials.widgets')
  </div><!-- /.col -->
</div><!-- /.row -->
@if (isset($widgetnode))
<div class="row">
  <div class="col-md-12">
    @include('backend::page._partials.widgetSetting')
  </div>
</div>
@endif
@endsection
