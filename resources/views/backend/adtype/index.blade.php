@extends('backend::layout')
@section('page_header', trans("backend.adtype.ad_type"))
@section('page_description', trans("backend.adtype.ad_type_list"))
@section('page_right')
<a href="{{ route('zxadmin.adtype.index') }}" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs">{!! trans('backend.adtype.list') !!}</span></a>
<a href="{{ route('zxadmin.adtype.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.adtype.add') !!}</span></a>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
      <div class="box-body no-padding">
      @if (count($adtypes))
        <div class="checkbox-auto-toggle">
          <table class="table table-striped">
          <tr>
            <th style="width: 10px"></th>
            <th>{!! trans('backend.adtype.ad_type_name') !!}</th>
            <th>{!! trans("backend.adtype.display_time") !!}</th>
            <th>{!! trans("backend.adtype.price") !!}</th>
            <th style="width: 40px"></th>
            <th style="width: 40px"></th>
          </tr>
          @foreach ($adtypes as $adtype)
          <tr data-element-parent-action data-id="{{ $adtype->id }}" data-title="{{ str_limit($adtype->title, 20) }}">
            <td><input type="checkbox" class="flat-red" /></td>
            <td>{{ $adtype->title }}</td>
            <td>
            @if ($adtype->nbr_days >= 9999)
            <small class="label bg-green">{{ mb_strtoupper(trans('backend.adtype.unlimited')) }}</small>
            @else
            {!! trans_choice('backend.adtype.nbr_days', $adtype->nbr_days) !!}
            @endif
            </td>
            @if ($adtype->price > 0)
            <td>{{ number_format($adtype->price, 2 , trans('backend.format.number.dec_point'), trans('backend.format.number.thousands_sep')) }} {{ setting('currency') }}</td>
            @else
            <td><span class="label bg-green">{{ trans('backend.adtype.free') }}</span></td>
            @endif
            <td><a href="{{ route('zxadmin.adtype.edit', $adtype->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> {!! trans('backend.adtype.edit') !!}</span></a></td>
            <td>
              <a href="#" class="btn btn-xs btn-danger" data-element-action data-element-action-text='{!! trans("backend.adtype.deleted_ad_type") !!}' data-element-action-route = '{{ route("zxadmin.adtype.destroy", [$adtype->id]) }}' data-toggle="modal" data-target="#confirmDeleteAction" data-title="{{ $adtype->title }}" data-message="{!! trans('backend.adtype.delete_ad_type_confirmation') !!}"><i class="fa fa-remove"></i> {!! trans('backend.adtype.delete') !!}</a>
            </td>
          </tr>
          @endforeach
          </table>
        </div>
      @else
        <br />
        <p class="text-center">{!! trans('backend.adtype.empty_adtypes_text') !!}</p>
        <br />
      @endif
      </div><!-- /.box-body -->
      @if (count($adtypes))
      <div class="box-footer no-padding">
        <div class="mailbox-controls">
          <!-- Check all button -->
          <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
          <span><button class="btn btn-danger btn-sm" data-elements-action data-elements-action-text='{!! trans("backend.adtype.js.nbr_deleted_ad_type") !!}' data-elements-action-route = '{{ route("zxadmin.adtype.destroy", ["_elements_"]) }}' data-toggle="modal" data-target="#confirmDeleteAction" data-title="{!! trans('backend.adtype.delete_many_ad_type') !!}" data-message="{!! trans('backend.adtype.delete_ad_types_confirmation') !!}"><i class="fa fa-remove"></i><span class="hidden-xs"> {!! trans('backend.adtype.delete') !!}</span></button></span>
          <div class="pull-right">
            {!! with(new ZEDx\Utils\Pagination($adtypes->appends(['q' => Request::get('q')])))->render() !!}
          </div><!-- /.pull-right -->
        </div>
      </div>
      @endif
    </div><!-- /.box -->
  </div>
</div>
@include('backend::adtype.modals.delete')
@endsection
