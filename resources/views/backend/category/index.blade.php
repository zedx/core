@extends('backend::layout')
@section('page_header', trans("backend.category.category"))
@section('page_description', trans("backend.category.category_list"))
@section('page_right')
<a href="{{ route('zxadmin.category.index') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans("backend.category.list") !!}</span></a>
<a href="{{ route('zxadmin.category.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans("backend.category.add") !!}</span></a>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">
        {!! trans('backend.category.category_list') !!}
        </h3>
        <menu id="nestable-menu" class="pull-right">
          <button type="button" class="btn btn-info" data-action="expand-all"><i class="fa fa-plus"></i> {!! trans('backend.category.expand_all') !!}</button>
          <button type="button" class="btn btn-info" data-action="collapse-all"><i class="fa fa-minus"></i> {!! trans('backend.category.collapse_all') !!}</button>
        </menu>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div id="nestableList" class="dd" data-url = "{{ route('zxadmin.category.index') }}">
          <ul class="dd-list">
          @foreach ($categories as $category)
            {!! renderNode($category, "category") !!}
          @endforeach
          </ol>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
</div>
@include('backend::category.modals.delete')
@endsection
