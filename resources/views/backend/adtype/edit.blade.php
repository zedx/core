@extends('backend::layout')
@section('page_header', trans("backend.adtype.ad_type"))
@section('page_description', trans("backend.adtype.edit_an_ad_type"))
@section('page_right')
<a href="{{ route('zxadmin.adtype.index') }}" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs">{!! trans('backend.adtype.list') !!}</span></a>
<a href="{{ route('zxadmin.adtype.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.adtype.add') !!}</span></a>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">
      </div><!-- /.box-header -->
      {!! Form::model($adtype, ['method' => 'PATCH', 'route' => ['zxadmin.adtype.update', $adtype->id]]) !!}
      @include('backend::adtype._form', array("submitButton" => trans("backend.adtype.edit")))
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
