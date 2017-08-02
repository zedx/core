@extends('backend::layout')
@section('page_header', trans("backend.setting.settings"))
@section('page_description', trans("backend.setting.manage_settings"))
@section('content')
<div class="row">
  <div class="col-md-12">
  {!! Form::model($setting, ['method' => 'PATCH', 'files' => true, "class"=>"form-horizontal", 'route' => ['zxadmin.setting.update']]) !!}
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom" id="setting-tab">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#setting_general" data-toggle="tab"><i class="fa fa-cogs"></i> {!! trans("backend.setting.general") !!}</a></li>
        <li><a href="#setting_ads" data-toggle="tab"><i class="fa fa-paper-plane-o"></i> {!! trans("backend.setting.ads") !!}</a></li>
        <li><a href="#setting_auth" data-toggle="tab"><i class="fa fa-key"></i> {!! trans("backend.setting.auth") !!}</a></li>
        <li><a href="#setting_notifications" data-toggle="tab"><i class="fa fa-bell-o"></i> {!! trans("backend.setting.notifications") !!}</a></li>
        <li><a href="#setting_moderation" data-toggle="tab"><i class="fa fa-bell-o"></i> {!! trans("backend.setting.moderation") !!}</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="setting_general">
          @include('backend::setting._partials.general')
        </div><!-- /.tab-pane -->
        <div class="tab-pane" id="setting_ads">
          @include('backend::setting._partials.ads')
        </div><!-- /.tab-pane -->
        <div class="tab-pane" id="setting_auth">
          @include('backend::setting._partials.auth')
        </div><!-- /.tab-pane -->
        <div class="tab-pane" id="setting_notifications">
           @include('backend::setting._partials.notificationsAdmin')
           <br />
           @include('backend::setting._partials.notificationsUser')
        </div><!-- /.tab-pane -->
        <div class="tab-pane" id="setting_moderation">
           @include('backend::setting._partials.moderation')
        </div><!-- /.tab-pane -->
        @include('backend::errors.list')
      </div><!-- /.tab-content -->
      @if ($errors->has('demo_update_settings'))
      <div class="alert alert-danger">
          {!! $errors->first('demo_update_settings') !!}
      </div>
      @endif
      <div class="box-footer clearfix">
        <button class="pull-right btn btn-primary">{!! trans('backend.setting.save') !!}</button>
      </div>
    </div><!-- nav-tabs-custom -->

  {!! Form::close() !!}
  </div><!-- /.col -->
</div>
@endsection
