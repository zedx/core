@extends('backend::layout')
@section('page_header', trans("backend.user.user"))
@section('page_description', trans("backend.user.user_list"))
@section('page_right')
<a href="{{ route('zxadmin.user.index') }}" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs">{!! trans("backend.user.list") !!}</span></a>
<a href="{{ route('zxadmin.user.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans("backend.user.add") !!}</span></a>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
      <div class="box-body no-padding">
      @if (count($users))
        <div class="checkbox-auto-toggle">
          <table class="table table-striped">
          <tr>
            <th style="width: 10px"></th>
            <th>{!! trans("backend.user.name") !!}</th>
            <th>{!! trans("backend.user.status") !!}</th>
            <th>{!! trans("backend.user.nbr_ads") !!}</th>
            <th>{!! trans("backend.user.subscribe") !!}</th>
            <th style="width: 40px"></th>
            <th style="width: 40px"></th>
          </tr>
          @foreach ($users as $user)
          <tr data-element-parent-action data-id="{{ $user->id }}" data-title="{{ str_limit($user->name, 20) }}">
            <td><input type="checkbox" class="flat-red" /></td>
            <td>{{ $user->name }}</td>
            <td>
            @if ($user->status == 1)
            <i class="fa fa-briefcase" data-toggle="tooltip" data-original-title="{!! trans('Professionnel') !!}"></i>
            @else
            <i class="fa fa-user tooltips" data-toggle="tooltip" data-original-title="{!! trans('Particulier') !!}"></i>
            @endif
            </td>
            <td>{{ $user->ads->count() }}</td>
            <td>{{ $user->created_at->diffForHumans() }}</td>
            <td><a href="{{ route('zxadmin.user.edit', $user->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Modifier</span></a></td>
            <td>
            <a href="#" class="btn btn-xs btn-danger" data-element-action data-element-action-text='{!! trans("backend.user.deleted_user") !!}' data-element-action-route = '{{ route("zxadmin.user.destroy", [$user->id]) }}' data-toggle="modal" data-target="#confirmDeleteAction" data-title="{{ $user->name }}" data-message="{!! trans('backend.user.delete_user_confirmation') !!}"><i class="fa fa-remove"></i> {!! trans('backend.user.delete') !!}</a>
            </td>
          </tr>
          @endforeach
          </table>
        <div>
      @else
        <br />
        <p class="text-center">{!! trans('backend.user.empty_users_text') !!}</p>
        <br />
      @endif
      </div><!-- /.box-body -->
      @if (count($users))
      <div class="box-footer no-padding">
        <div class="mailbox-controls">
          <!-- Check all button -->
          <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
          <span><button class="btn btn-danger btn-sm" data-elements-action data-elements-action-text='{!! trans("backend.user.js.nbr_deleted_user") !!}' data-elements-action-route = '{{ route("zxadmin.user.destroy", ["_elements_"]) }}' data-toggle="modal" data-target="#confirmDeleteAction" data-title="{!! trans('backend.user.delete_many_users') !!}" data-message="{!! trans('backend.user.delete_users_confirmation') !!}"><i class="fa fa-remove"></i><span class="hidden-xs"> {!! trans('backend.user.delete') !!}</span></button></span>
          <div class="pull-right">
            {!! with(new ZEDx\Utils\Pagination($users->appends(['q' => Request::get('q')])))->render() !!}
          </div><!-- /.pull-right -->
        </div>
      </div>
      @endif
    </div><!-- /.box -->
  </div>
</div>
@include('backend::user.modals.delete')
@endsection
