@extends('backend::layout')
@section('page_header', trans("backend.page.page"))
@section('page_description', trans("backend.page.page_list"))
@section('page_right')
<a href="{{ route('zxadmin.page.index') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans('backend.page.list') !!}</span></a>
<a href="{{ route('zxadmin.page.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.page.add') !!}</span></a>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs pull-s">
        <li @if (Route::is('zxadmin.page.index')) class="active" @endif><a href="{{ route('zxadmin.page.index') }}" class="text-blue"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans('backend.page.my_pages') !!}</span></a></li>
        <li @if (Route::is('zxadmin.page.core')) class="active" @endif><a href="{{ route('zxadmin.page.core') }}" class="text-red"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans('backend.page.core_pages') !!}</span></a></li>
        <li class="pull-right col-sm-12 col-xs-12 col-sm-4 col-md-3">
          <form action="{{ Request::url() }}" >
           <div class="input-group">
             <input type="text" name="q" class="form-control input-sm pull-right" value="{{ Request::get('q') }}" />
             <div class="input-group-btn">
               <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
             </div>
           </div>
          </form>
        </li>
      </ul>
      <div class="tab-content no-padding">
        <div class="box box-solid">
          <div class="box-body no-padding">
            @if (count($pages))
            <div class="checkbox-auto-toggle">
              <table class="table table-striped">
              <tr>
                <th style="width: 10px"></th>
                <th>{!! trans("backend.page.name") !!}</th>
                <th>{!! trans("backend.page.description") !!}</th>
                @if ($type != 'page')
                <th>{!! trans("backend.page.route") !!}</th>
                @else
                <th>{!! trans("backend.page.shortcut") !!}</th>
                @endif
                @if ($type == 'page')
                <th>{!! trans("backend.page.home_page") !!}</th>
                @endif
                <th style="width: 40px"></th>
                @if ($type == 'page')
                <th style="width: 40px"></th>
                @endif
              </tr>
              @foreach ($pages as $page)
              <tr data-element-parent-action data-id="{{ $page->id }}" data-title="{{ str_limit($page->name, 20) }}">
                <td>
                @if ($type == 'page')
                <input type="checkbox" class="flat-red" />
                @endif
                </td>
                <td>{{ $page->name }}</td>
                <td>{{ $page->description }}</td>
                @if ($type != 'page')
                <td><i class="fa fa-link"></i> {{ $page->shortcut }}</td>
                @else
                <td><a href="{{ route('page.show', $page->shortcut) }}" target="_blank"><i class="fa fa-link"></i> {{ $page->shortcut }}</a></td>
                @endif
                @if ($type == 'page')
                <td><input type="radio" {{ $page->is_home ? 'checked' : '' }} data-url="{{ route('zxadmin.page.beHomepage', $page->id) }}" class="flat-red homepage-switch" name="is_home"></td>
                @endif
                <td><a href="{{ route('zxadmin.page.edit', [$page->id, $page->template->blocks()->firstOrFail()->identifier]) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> {!! trans('backend.page.edit') !!}</span></a></td>
                @if ($type == 'page')
                <td>
                  <a href="#" class="btn btn-xs btn-danger" data-element-action data-element-action-text='{!! trans("backend.page.deleted_page") !!}' data-element-action-route = '{{ route("zxadmin.page.destroy", [$page->id]) }}' data-toggle="modal" data-target="#confirmDeleteAction" data-title="{{ $page->name }} [{{ $page->title }}]" data-message="{!! trans('backend.page.delete_page_confirmation') !!}"><i class="fa fa-remove"></i> {!! trans('backend.page.delete') !!}</a>
                </td>
                @endif
              </tr>
              @endforeach
              </table>
            </div>
            @else
            <br />
            <p class="text-center">{!! trans('backend.page.empty_pages_text') !!}</p>
            <br />
            @endif
          </div><!-- /.box-body -->
          @if (count($pages))
          <div class="box-footer no-padding">
            <div class="mailbox-controls">
              <!-- Check all button -->
              @if ($type == 'page')
              <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
              <span><button class="btn btn-danger btn-sm" data-elements-action data-elements-action-text='{!! trans("backend.page.js.nbr_deleted_page") !!}' data-elements-action-route = '{{ route("zxadmin.page.destroy", ["_elements_"]) }}' data-toggle="modal" data-target="#confirmDeleteAction" data-title="{!! trans('backend.page.delete_many_pages') !!}" data-message="{!! trans('backend.page.delete_pages_confirmation') !!}"><i class="fa fa-remove"></i><span class="hidden-xs"> {!! trans('backend.page.delete') !!}</span></button></span>
              @endif
              <div class="pull-right">
                {!! with(new ZEDx\Utils\Pagination($pages->appends(['q' => Request::get('q')])))->render() !!}
              </div><!-- /.pull-right -->
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@include('backend::page.modals.delete')
@endsection
