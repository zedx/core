@extends('backend::layout')
@section('page_header', trans("backend.user.user"))
@section('page_description', trans("backend.user.add_a_user"))
@section('page_right')
<a href="{{ route('zxadmin.user.index') }}" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs">{!! trans('backend.user.list') !!}</span></a>
<a href="{{ route('zxadmin.user.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.user.add') !!}</span></a>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
      <div class="box-header">
      </div><!-- /.box-header -->
      {!! Form::open(['route' => 'zxadmin.user.store']) !!}
      @include('backend::user._form', ["submitButton" => trans('backend.user.add_user')])
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
