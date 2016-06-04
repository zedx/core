@extends('backend::layout')
@section('page_header', trans("backend.template.template"))
@section('page_description', trans("backend.template.template_list"))
@section('page_right')
<a href="{{ route('zxadmin.template.index') }}" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs">{!! trans('backend.template.list') !!}</span></a>
<a href="{{ route('zxadmin.template.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.template.add') !!}</span></a>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
      <div class="box-body no-padding">
        <div class="checkbox-auto-toggle">
          <table class="table table-striped">
          <tr>
            <th style="width: 10px"></th>
            <th>{!! trans("backend.template.title") !!}</th>
            <th style="width: 40px"></th>
            <th style="width: 40px"></th>
          </tr>
          @foreach ($templates as $template)
          <tr data-element-parent-action data-id="{{ $template->id }}" data-title="{{ str_limit($template->title, 20) }}">
            <td><input type="checkbox" class="flat-red" /></td>
            <td>{{ $template->title }}</td>
            <td><a href="{{ route('zxadmin.template.edit', $template->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> {!! trans('backend.template.personalize') !!}</span></a></td>
            <td>
              <a href="#" class="btn btn-xs btn-danger" data-element-action data-element-action-text='{!! trans("backend.template.deleted_template") !!}' data-element-action-route = '{{ route("zxadmin.template.destroy", [$template->id]) }}' data-toggle="modal" data-target="#confirmDeleteAction" data-title="{{ $template->title }}" data-message="{!! trans('backend.template.delete_template_confirmation') !!}"><i class="fa fa-remove"></i> {!! trans('backend.template.delete') !!}</a>
            </td>
          </tr>
          @endforeach
          </table>
        </div>
      </div><!-- /.box-body -->
      @if (count($templates))
      <div class="box-footer no-padding">
        <div class="mailbox-controls">
          <!-- Check all button -->
          <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
          <span><button class="btn btn-danger btn-sm" data-elements-action data-elements-action-text='{!! trans("backend.template.js.nbr_deleted_template") !!}' data-elements-action-route = '{{ route("zxadmin.template.destroy", ["_elements_"]) }}' data-toggle="modal" data-target="#confirmDeleteAction" data-title="{!! trans('backend.template.delete_many_templates') !!}" data-message="{!! trans('backend.template.delete_templates_confirmation') !!}"><i class="fa fa-remove"></i><span class="hidden-xs"> {!! trans('backend.template.delete') !!}</span></button></span>
          <div class="pull-right">
            {!! with(new ZEDx\Utils\Pagination($templates->appends(['q' => Request::get('q')])))->render() !!}
          </div><!-- /.pull-right -->
        </div>
      </div>
      @endif
    </div><!-- /.box -->
  </div>
</div>
@include('backend::template.modals.delete')
@endsection
