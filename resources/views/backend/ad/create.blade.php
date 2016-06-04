@extends('backend::layout')
@section('page_header', trans("backend.ad.ad"))
@section('page_description', trans("backend.ad.add_an_ad"))
@section('page_right')
<a href="{{ route('zxadmin.ad.status', 'pending') }}" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs">{!! trans('backend.ad.list') !!}</span></a>
<a href="{{ route('zxadmin.ad.choose') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.ad.add') !!}</span></a>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">{!! trans("backend.ad.ad_details") !!}</h3>
      </div><!-- /.box-header -->
      {!! Form::open(['route' => ['zxadmin.ad.store', $adtype->id], 'files' => true]) !!}
      @include('backend::ad._form', array("submitButton" => trans('backend.ad.add_ad')))
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
