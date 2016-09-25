@extends('backend::layout')
@section('page_header', trans("backend.theme.theme"))
@section('page_description', trans("backend.theme.theme_list"))
@section('page_right')
<a href="{{ route('zxadmin.theme.index') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans('backend.theme.list') !!}</span></a>
<a href="{{ route('zxadmin.theme.add') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.theme.add') !!}</span></a>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-success">
      <div class="box-body">
        @include('backend::theme._partials.currentTheme')
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
</div>
@if (count($themes))
<div class="row" id="themesTable" data-seturl="{{ route('zxadmin.theme.set') }}">
  <div class="col-md-12">
    <h3>{!! trans('backend.theme.available_themes') !!}</h3>
    <hr>
    <div class="row">
      @include('backend::theme._partials.themesList')
    </div>
  </div>
</div>
@endif
@include('backend::theme.modals.delete')
@endsection
