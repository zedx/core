@extends('backend::layout')
@section('page_header', trans("backend.field.advanced_field"))
@section('page_description', trans("backend.field.create_a_field"))
@section('page_right')
<a href="{{ route('zxadmin.field.index') }}" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs">{!! trans("backend.field.list") !!}</span></a>
<a href="{{ route('zxadmin.field.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans("backend.field.add") !!}</span></a>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
      <div class="box-header">
      </div><!-- /.box-header -->
      {!! Form::open(array('route' => 'zxadmin.field.store')) !!}
      @include('backend::field._form', array("submitButton" => trans('backend.field.add_field')))
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
