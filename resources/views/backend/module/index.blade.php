@extends('backend::layout')
@section('page_header', trans("backend.module.module"))
@section('page_description', trans("backend.module.list_modules"))
@section('page_right')
<a href="{{ route('zxadmin.module.index') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{{ trans('backend.module.list') }}</span></a>
<a href="{{ route('zxadmin.module.add') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{{ trans('backend.module.add') }}</span></a>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="nav-tabs-custom">
      <!-- Tabs within a box -->
      <ul class="nav nav-tabs pull-s">
        <li @if (Route::is('zxadmin.module.index')) class="active" @endif><a href="{{ route('zxadmin.module.index') }}"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{{ trans('backend.module.all_modules') }}</span></a></li>
        <li @if (isset($status) && $status == 'enabled') class="active" @endif><a href="{{ route('zxadmin.module.status', 'enabled') }}" class="text-green"><i class="fa fa-check"></i> <span class="hidden-xs hidden-sm">{{ trans('backend.module.enabled') }}</span></a></li>
        <li @if (isset($status) && $status == 'disabled') class="active" @endif><a href="{{ route('zxadmin.module.status', 'disabled') }}" class="text-orange"><i class="fa fa-ban"></i> <span class="hidden-xs hidden-sm">{{ trans('backend.module.disabled') }}</span></a></li>
      </ul>
      <div class="tab-content no-padding">
        <div class="box box-solid">
          <div class="box-body">
            @forelse ($modules as $module)
              <div class="col-sm-6 col-xs-12 col-md-3" data-element-parent-action data-id="{{ $module->get('title') }}" data-title="{{ str_limit($module->get('title'), 20) }}">
                <div class="thumbnail">
                  <img src="{{ route('zxadmin.module.screenshot', $module->get('name')) }}" alt="{{ $module->get('title') }}">
                  <div class="caption">
                    <h3>
                    @if (Route::has('module_'.$module->getLowerName().'.admin.index'))
                    <a href="{{ route('module_'.$module->getLowerName().'.admin.index') }}">{{ str_limit($module->get('title'), 18) }}</a>
                    @else
                    {{ str_limit($module->get('title'), 18) }}
                    @endif
                    </h3>
                    <p><span class="label label-info">{{ trans('backend.module.author', ['author' => $module->get('author')]) }}</span> <span class="label label-primary">{{ trans('backend.module.version', ['version' => $module->get('version')]) }}</span></p>
                    <p>{{ str_limit($module->get('description'), 90) }}</p>
                    @if ($module->active())
                      @if (Route::has('module_'.$module->getLowerName().'.admin.config'))
                      <a href="{{ route('module_'.$module->getLowerName().'.admin.config') }}" class="btn btn-primary btn-xs" > {!! trans("backend.module.configure") !!}</a>
                      @endif
                      <a href="#" class="plugin-switch-status btn btn-warning btn-xs" data-url="{{ route('zxadmin.module.switchStatus', [$module->getName()]) }}"> {!! trans('backend.module.disable') !!}</a>
                      @else
                      <a href="#" class="plugin-switch-status btn btn-success btn-xs" data-url="{{ route('zxadmin.module.switchStatus', [$module->getName()]) }}"> {!! trans('backend.module.enable') !!}</a>
                      @endif
                      <span><a href="#" class="btn btn-danger btn-xs" data-element-action data-element-action-text='{!! trans("backend.module.deleted_module") !!}' data-element-action-route = '{{ route("zxadmin.module.destroy", [$module->get('name')]) }}' data-toggle="modal" data-target="#confirmDeleteAction" data-title="{{ $module->get('title') }}" data-message="{!! trans('backend.module.delete_module_confirmation') !!}" > {!! trans('backend.module.delete') !!}</a></span>
                  </div>
                </div>
              </div>
            @empty
            <br />
              <p class="text-center">{!! trans('backend.module.empty_modules_text') !!}</p>
            <br />
            @endforelse
          </div><!-- /.box-body -->
        </div><!-- /. box -->
      </div>
    </div><!-- /.nav-tabs-custom -->
  </div><!-- /.col -->
</div>
@include('backend::module.modals.delete')
@endsection
