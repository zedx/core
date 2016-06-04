@extends('backend::layout')
@section('page_header', trans("backend.user.user"))
@section('page_description', trans("backend.user.edit_a_user"))
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
      {!! Form::model($user, ['method' => 'PATCH', 'route' => ['zxadmin.user.update', $user->id]]) !!}
      @include('backend::user._form', array("submitButton" => trans("backend.user.edit")))
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
