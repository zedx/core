@extends('backend::layout')
@section('page_header', trans("backend.template.template"))
@section('page_description', trans("backend.template.edit_a_template"))
@section('page_right')
<a href="{{ route('zxadmin.template.index') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans('backend.template.list') !!}</span></a>
<a href="{{ route('zxadmin.template.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.template.add') !!}</span></a>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">
      </div><!-- /.box-header -->
      {!! Form::model($template, ['method' => 'PATCH', 'route' => ['zxadmin.template.update', $template->id]]) !!}
      @include('backend::template._form', array("submitButton" => trans("backend.template.edit_template")))
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
