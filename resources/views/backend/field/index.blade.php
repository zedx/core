@extends('backend::layout')
@section('page_header', trans("backend.field.advanced_field"))
@section('page_description', trans("backend.field.field_list"))
@section('page_right')
<a href="{{ route('zxadmin.field.index') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans("backend.field.list") !!}</span></a>
<a href="{{ route('zxadmin.field.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans("backend.field.add") !!}</span></a>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
      <div class="box-body no-padding">
        @if (count($fields))
        <div class="checkbox-auto-toggle">
          <table class="table table-striped">
          <tr>
            <th style="width: 10px"></th>
            <th>{!! trans("backend.field.field_name") !!}</th>
            <th>{!! trans("backend.field.unit") !!}</th>
            <th>{!! trans("backend.field.title") !!}</th>
            <th style="width: 40px"></th>
            <th style="width: 40px"></th>
          </tr>
          @foreach ($fields as $field)
          <tr data-element-parent-action data-id="{{ $field->id }}" data-title="{{ str_limit($field->name, 20) }}">
            <td><input type="checkbox" class="flat-red" /></td>
            <td>{{ $field->name }}</td>
            <td>{{ $field->unit }}</td>
            <td>{{ $field->title }}</td>
            <td><a href="{{ route('zxadmin.field.edit', $field->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> {!! trans('backend.field.edit') !!}</span></a></td>
            <td>
              <a href="#" class="btn btn-xs btn-danger" data-element-action data-element-action-text='{!! trans("backend.field.deleted_field") !!}' data-element-action-route = '{{ route("zxadmin.field.destroy", [$field->id]) }}' data-toggle="modal" data-target="#confirmDeleteAction" data-title="{{ $field->name }} [{{ $field->title }}]" data-message="{!! trans('backend.field.delete_field_confirmation') !!}"><i class="fa fa-remove"></i> {!! trans('backend.field.delete') !!}</a>
            </td>
          </tr>
          @endforeach
          </table>
        </div>
        @else
        <br />
        <p class="text-center">{!! trans('backend.field.empty_fields_text') !!}</p>
        <br />
        @endif
      </div><!-- /.box-body -->
      @if (count($fields))
      <div class="box-footer no-padding">
        <div class="mailbox-controls">
          <!-- Check all button -->
          <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
          <span><button class="btn btn-danger btn-sm" data-elements-action data-elements-action-text='{!! trans("backend.field.js.nbr_deleted_field") !!}' data-elements-action-route = '{{ route("zxadmin.field.destroy", ["_elements_"]) }}' data-toggle="modal" data-target="#confirmDeleteAction" data-title="{!! trans('backend.field.delete_many_fields') !!}" data-message="{!! trans('backend.field.delete_fields_confirmation') !!}"><i class="fa fa-remove"></i><span class="hidden-xs"> {!! trans('backend.field.delete') !!}</span></button></span>
          <div class="pull-right">
            {!! with(new ZEDx\Utils\Pagination($fields->appends(['q' => Request::get('q')])))->render() !!}
          </div><!-- /.pull-right -->
        </div>
      </div>
      @endif
    </div><!-- /.box -->
  </div>
</div>
@include('backend::field.modals.delete')
@endsection
