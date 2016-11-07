@extends('backend::layout')
@section('page_header', trans("backend.menu.menu"))
@section('page_description', trans("backend.menu.personalize_menu"))

@section('content')
<div class="row">
	<div class="col-md-4">
    <div class="row">
      <div class="col-md-12">
        @include('backend::menu._partials.link')
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        @include('backend::menu._partials.page')
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        @include('backend::menu._partials.route')
      </div>
    </div>
  </div>
  <div class="col-md-8">
    @include('backend::menu._partials.list')
  </div>
</div>
@include('backend::menu.modals.delete')
@include('backend::menu.modals.edit')
@endsection
