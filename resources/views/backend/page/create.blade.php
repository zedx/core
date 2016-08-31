@extends('backend::layout')
@section('page_header', trans("backend.page.page"))
@section('page_description', trans("backend.page.create_a_page"))
@section('page_right')
<a href="{{ route('zxadmin.page.index') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans('backend.page.list') !!}</span></a>
<a href="{{ route('zxadmin.page.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.page.add') !!}</span></a>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
      <div class="box-header">
      </div><!-- /.box-header -->
      {!! Form::open(array('route' => 'zxadmin.page.store')) !!}
      @include('backend::page._form', array("submitButton" => trans('backend.page.add_page')))
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
