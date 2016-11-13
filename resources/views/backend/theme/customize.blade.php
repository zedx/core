@extends('backend::layout')
@section('page_header', trans("backend.theme.theme"))
@section('page_description', trans("backend.theme.customize"))
@section('page_right')
<a href="{{ route('zxadmin.theme.index') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans('backend.theme.list') !!}</span></a>
<a href="{{ route('zxadmin.theme.add') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.theme.add') !!}</span></a>
@endsection

@section('content')

@if (Widgets::exists('Frontend\Theme\Customize'))
    @widget('Frontend\Theme\Customize', [])
@else
    <center><div class="alert alert-info">{!! trans('backend.theme.no_customize') !!}</div></center>
@endif

@endsection
