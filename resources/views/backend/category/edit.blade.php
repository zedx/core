@extends('backend::layout')
@section('page_header', trans("backend.category.category"))
@section('page_description', trans("backend.category.edit_a_category"))
@section('page_right')
<a href="{{ route('zxadmin.category.index') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans("backend.category.list") !!}</span></a>
<a href="{{ route('zxadmin.category.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans("backend.category.add") !!}</span></a>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">
      </div><!-- /.box-header -->
      {!! Form::model($category, ['method' => 'PATCH', 'files' => true, 'route' => ['zxadmin.category.update', $category->id]]) !!}
      @include('backend::category._form', array("submitButton" => trans("backend.category.edit_category")))
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
