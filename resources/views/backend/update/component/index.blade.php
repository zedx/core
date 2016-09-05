@extends('backend::layout')
@section('page_header', trans("backend.update.update_system"))
@section('page_description', trans("backend.update.components.{$type}.title"))
@section('page_right')

@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">{{ trans("backend.update.components.{$type}.list") }}</h3>
        </div>
        @if(!empty($updatesList[$type]))
        <div class="box-body no-padding">
          <ul class="nav nav-stacked">
            @foreach($updatesList[$type] as $component)
            <li><a href="{{ route('zxadmin.update.show', $type) }}?namespace={{ $component['namespace'] }}">{{ $component['namespace'] }} <span class="badge bg-blue"> {{ $component['version'] }} </span> <button class="btn btn-success btn-sm pull-right"> Plus de détails </button></a></li>
            @endforeach
          </ul>
        </div>
        @else
        <div class="box-body">
          <center>{{ trans('backend.update.components.no_updates') }}</center>
        </div>
        @endif
        <!-- /.box-body -->
    </div>
  </div>
</div>
@endsection


