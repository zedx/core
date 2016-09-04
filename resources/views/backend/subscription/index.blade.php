@extends('backend::layout')
@section('page_header', trans("backend.subscription.subscription"))
@section('page_description', trans("backend.subscription.subscription_list"))
@section('page_right')
<a href="{{ route('zxadmin.subscription.index') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans('backend.subscription.list') !!}</span></a>
<a href="{{ route('zxadmin.subscription.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.subscription.add') !!}</span></a>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
        <div class="box-header">
         <form action="{{ Request::url() }}" >
           <div class="input-group">
             <input type="text" name="q" class="form-control input-sm pull-right" value="{{ Request::get('q') }}" />
             <div class="input-group-btn">
               <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
             </div>
           </div>
         </form>
        </div>
      <div class="box-body no-padding">
        @if (count($subscriptions))
        <div class="checkbox-auto-toggle">
          <table class="table table-striped">
          <tr>
            <th style="width: 10px"></th>
            <th>{!! trans("backend.subscription.name") !!}</th>
            <th>{!! trans("backend.subscription.description") !!}</th>
            <th>{!! trans("backend.subscription.display_time") !!}</th>
            <th>{!! trans("backend.subscription.price") !!}</th>
            <th style="width: 40px"></th>
            <th style="width: 40px"></th>
          </tr>
          @foreach ($subscriptions as $subscription)
          <tr data-element-parent-action data-id="{{ $subscription->id }}" data-title="{{ str_limit($subscription->title, 20) }}">
            <td><input type="checkbox" class="flat-red" /></td>
            <td>{{ $subscription->title }}</td>
            <td>{{ $subscription->description }}</td>
            <td>
            @if ($subscription->days >= 9999)
              <small class="label bg-green">{{ mb_strtoupper(trans('backend.subscription.unlimited')) }}</small>
              @else
              {!! trans_choice('backend.subscription.nbr_days', $subscription->days) !!}
              @endif
            </td>
            @if ($subscription->price > 0)
            <td>{{ number_format($subscription->price, 2 , trans('backend.format.number.dec_point'), trans('backend.format.number.thousands_sep')) }} {{ setting('currency') }}</td>
            @else
            <td><span class="label bg-green">{{ trans('backend.subscription.free') }}</span></td>
            @endif
            <td><a href="{{ route('zxadmin.subscription.edit', $subscription->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> {!! trans('backend.subscription.edit') !!}</span></a></td>
            <td>
              <a href="#" class="btn btn-xs btn-danger" data-element-action data-element-action-text='{!! trans("backend.subscription.deleted_subscription") !!}' data-element-action-route = '{{ route("zxadmin.subscription.destroy", [$subscription->id]) }}' data-toggle="modal" data-target="#confirmDeleteAction" data-title="{{ $subscription->title }}" data-message="{!! trans('backend.subscription.delete_subscription_confirmation') !!}"><i class="fa fa-remove"></i> {!! trans('backend.subscription.delete') !!}</a>
            </td>
          </tr>
          @endforeach
          </table>
        </div>
        @else
        <br />
        <p class="text-center">{!! trans('backend.subscription.empty_subscriptions_text') !!}</p>
        <br />
        @endif
      </div><!-- /.box-body -->
      @if (count($subscriptions))
      <div class="box-footer no-padding">
        <div class="mailbox-controls">
          <!-- Check all button -->
          <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
          <span><button class="btn btn-danger btn-sm" data-elements-action data-elements-action-text='{!! trans("backend.subscription.js.nbr_deleted_subscription") !!}' data-elements-action-route = '{{ route("zxadmin.subscription.destroy", ["_elements_"]) }}' data-toggle="modal" data-target="#confirmDeleteAction" data-title="{!! trans('backend.subscription.delete_many_subscriptions') !!}" data-message="{!! trans('backend.subscription.delete_subscriptions_confirmation') !!}"><i class="fa fa-remove"></i><span class="hidden-xs"> {!! trans('backend.subscription.delete') !!}</span></button></span>
          <div class="pull-right">
            {!! with(new ZEDx\Utils\Pagination($subscriptions->appends(['q' => Request::get('q')])))->render() !!}
          </div><!-- /.pull-right -->
        </div>
      </div>
      @endif
    </div><!-- /.box -->
  </div>
</div>
@include('backend::subscription.modals.delete')
@endsection
