@extends('backend::layout')
@section('page_header', trans("backend.subscription.subscription"))
@section('page_description', trans("backend.subscription.create_a_subscription"))
@section('page_right')
<a href="{{ route('zxadmin.subscription.index') }}" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs">Liste</span></a>
<a href="{{ route('zxadmin.subscription.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">Ajouter</span></a>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
      <div class="box-header">
      </div><!-- /.box-header -->
      {!! Form::open(array('route' => 'zxadmin.subscription.store')) !!}
      @include('backend::subscription._form', array("submitButton" => trans('backend.subscription.add_subscription')))
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
