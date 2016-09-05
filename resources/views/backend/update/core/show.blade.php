@extends('backend::layout')
@section('page_header', trans("backend.update.update_system"))
@section('page_description', ucfirst($name))
@section('page_right')

@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <!-- Widget: user widget style 1 -->
    <div class="box box-widget widget-user-2">
      <!-- Add the bg color to the header using any of the bg-* classes -->
      <div class="widget-user-header bg-aqua-active">
        <div class="widget-user-image">
          <i class="fa fa-refresh pull-left" style="font-size:58px"></i>
        </div>
        <!-- /.widget-user-image -->
        <h3 class="widget-user-username">ZEDx</h3>
        <h5 class="widget-user-desc">{{ ZEDx\Core::VERSION }}</h5>
      </div>
      <div class="box-body">
        @if (Updater::isLatestPackage('core', 'ZEDx'))
        <div class="row">
          <div class="col-md-12">
            <h3 class="text-green"><center><i class="fa fa-check-circle-o"></i> {{ trans('backend.update.latest_version') }}</center></h3>
          </div>
        </div>
        @else
        <div class="row">
          <div class="col-md-6">
            @include('backend::update._patials.details')
          </div>
          <div class="col-md-6">
            @if (!empty($changedFiles) && !$force)
              @include('backend::update._patials.unconformfiles')
            @else
              @include('backend::update._patials.progress')
            @endif
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@if (!Updater::isLatestPackage('core', 'ZEDx'))
<div class="row">
  <div class="col-md-12">
    <div class="panel box box-primary">
      <div class="box-header with-border">
        <div class="panel-heading collapsed" data-toggle="collapse" data-parent="#accordion" data-target="#collapseOne">
            <h4 class="panel-title accordion-toggle">
              # {{ trans('backend.update.update_log') }}
            </h4>
        </div>
      </div>
      <div id="collapseOne" class="panel-collapse collapse">
        <div class="box-body">
        <pre id="updater-log">
        </pre>
        </div>
      </div>
    </div>
  </div>
</div>
@endif
@endsection
