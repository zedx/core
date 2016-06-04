@extends('backend::layout')
@section('page_header', trans("backend.theme.theme"))
@section('page_description', trans("backend.theme.add_a_theme"))
@section('page_right')
<a href="{{ route('zxadmin.theme.index') }}" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs">{{ trans("backend.theme.list") }}</span></a>
<a href="{{ route('zxadmin.theme.add') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{{ trans("backend.theme.add") }}</span></a>
@endsection

@section('content')
<div class="row">

  <div class="col-md-12">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li @if (isset($tab) && $tab == 'search') class="active" @endif><a href="{{ route('zxadmin.theme.addWithTab', 'search') }}"><i class="fa fa-search"></i><span class="hidden-sm hidden-xs"> {{ trans("backend.theme.search") }}</span></a></li>
        <!--
        <li @if (isset($tab) && $tab == 'upload') class="active" @endif><a href="{{ route('zxadmin.theme.addWithTab', 'upload') }}"><i class="fa fa-upload"></i><span class="hidden-sm hidden-xs"> Charger</span></a></li>
        -->
        <li @if (isset($tab) && $tab == 'api' && Request::get('sort') == 'popular') class="active" @endif><a href="{{ route('zxadmin.theme.addWithTab', 'api') . '?sort=popular' }}"><i class="fa fa-fire"></i><span class="hidden-sm hidden-xs"> {{ trans("backend.theme.most_popular") }}</span></a></li>
        <li @if (isset($tab) && $tab == 'api' && Request::get('sort') == 'newest') class="active" @endif><a href="{{ route('zxadmin.theme.addWithTab', 'api') . '?sort=newest' }}"><i class="fa fa-bullhorn"></i><span class="hidden-sm hidden-xs"> {{ trans("backend.theme.most_recent") }}</span></a></li>
        <li @if (isset($tab) && $tab == 'api' && Request::get('author') == 'zedx') class="active" @endif><a href="{{ route('zxadmin.theme.addWithTab', 'api') . '?author=zedx' }}">ZEDx</a></li>
      </ul>
      <div class="tab-content">
        @if ($tab == 'search')
          @include('backend::theme._partials.search')
        @elseif ($tab == 'upload')
          @include('backend::theme._partials.upload')
        @else
          @include('backend::theme._partials.externalList')
        @endif
      </div>
    </div>
  </div><!-- /.col -->
</div>
@endsection
