@extends('backend::layout')
@section('page_header', trans("backend.module.module"))
@section('page_description', trans("backend.module.list_modules"))
@section('page_right')
<a href="{{ route('zxadmin.module.index') }}" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs">{{ trans('backend.module.list') }}</span></a>
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
          <div class="box-body no-padding">
            <div class="checkbox-auto-toggle">
            @if (count($modules))
              <table class="table table-striped">
                <tr>
                  <th></th>
                  <th class="col-md-2">{!! trans("backend.module.name") !!}</th>
                  <th class="col-md-1">{!! trans("backend.module.version") !!}</th>
                  <th class="col-md-6">{!! trans("backend.module.description") !!}</th>
                  <th class="col-md-6">{!! trans("backend.module.is_enabled") !!}</th>
                  <th class="col-md-2"></th>
                </tr>
                @foreach ($modules as $module)
                <tr>
                  <td><input type="checkbox" class="flat-red" /></td>
                  <td><a href="{{ url('zxadmin/module/' . $module->getLowerName()) }}">{{ $module->get('title') }}</a></td>
                  <td>{{ $module->get('version') }}</td>
                  <td>{{ $module->get('description') }}</td>
                  <td>
                  <input class="plugin-switch-status bootstrap-switch" data-url="{{ route('zxadmin.module.switchStatus', [$module->getName()]) }}" data-size="mini" data-on-text="Oui" data-off-text="Non" data-off-color="danger" type="checkbox" {{ $module->active() ? 'checked' : ''}}>
                  </td>
                  <td class="pull-right">
                  <div class="btn-group">
                    <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i> {!! trans("backend.module.actions") !!} <span class="fa fa-caret-down"></span></button>
                    <ul class="dropdown-menu pull-right">
                      <li><a href="#" class="text-aqua"><i class="fa fa-wrench"></i> {!! trans("backend.module.configure") !!}</a></li>
                      <li><a href="#" class="text-green"><i class="fa fa-wrench"></i> {!! trans("backend.module.enable") !!}</a></li>
                      <li><a href="#" class="text-orange"><i class="fa fa-wrench"></i> {!! trans("backend.module.disable") !!}</a></li>
                      <li class="divider"></li>
                      <li><a href="#" class="text-red"><i class="fa fa-remove"></i> {!! trans("backend.module.delete") !!}</a></li>
                    </ul>
                  </div>
                  </td>
                </tr>
                @endforeach
              </table>
              @else
                <br />
                  <p class="text-center">{!! trans('backend.module.empty_modules_text') !!}</p>
                <br />
              @endif
            </div><!-- /.mail-box-messages -->
          </div><!-- /.box-body -->
          @if (count($modules))
          <div class="box-footer no-padding">
            <div class="mailbox-controls">
              <!-- Check all button -->
              <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
              <div class="btn-group">
                <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i> {!! trans('backend.module.actions') !!} <span class="fa fa-caret-down"></span></button>
                <ul class="dropdown-menu">
                  <li><a href="#" class="text-green"><i class="fa fa-check"></i> {!! trans('backend.module.enable') !!}</a></li>
                  <li><a href="#" class="text-orange"><i class="fa fa-ban"></i> {!! trans('backend.module.disable') !!}</a></li>
                  <li class="divider"></li>
                  <li><a href="#" class="text-red"><i class="fa fa-remove"></i> {!! trans('backend.module.delete') !!}</a></li>
                </ul>
              </div>
              <div class="pull-right">
                <ul class="pagination pagination-sm no-margin pull-right">
                  <li><a href="#">&laquo;</a></li>
                  <li><a href="#">1</a></li>
                  <li><a href="#">2</a></li>
                  <li><a href="#">3</a></li>
                  <li><a href="#">&raquo;</a></li>
                </ul>
              </div><!-- /.pull-right -->
            </div>
          </div>
          @endif

        </div><!-- /. box -->
      </div>
    </div><!-- /.nav-tabs-custom -->
  </div><!-- /.col -->
</div>
@endsection
