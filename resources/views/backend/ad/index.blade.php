@extends('backend::layout')
@section('page_header', trans("backend.ad.ad"))
@section('page_description', trans("backend.ad.ad_list"))
@section('page_right')
<a href="{{ route('zxadmin.ad.status', 'pending') }}" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs">{!! trans('backend.ad.list') !!}</span></a>
<a href="{{ route('zxadmin.ad.choose') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.ad.add') !!}</span></a>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="nav-tabs-custom">
      <!-- Tabs within a box -->
      <ul class="nav nav-tabs pull-s">
        <li @if (Route::is('zxadmin.ad.index')) class="active" @endif><a href="{{ route('zxadmin.ad.index') }}"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans('backend.ad.all_ads') !!}</span></a></li>
        <li @if (isset($adstatus) && $adstatus->title == 'pending') class="active" @endif><a href="{{ route('zxadmin.ad.status', 'pending') }}" class="text-blue"><i class="fa fa-hourglass-start"></i> <span class="hidden-xs hidden-sm">{!! trans('backend.ad.pending') !!}</span></a></li>
        <li @if (isset($adstatus) && $adstatus->title == 'validate') class="active" @endif><a href="{{ route('zxadmin.ad.status', 'validate') }}" class="text-green"><i class="fa fa-check"></i> <span class="hidden-xs hidden-sm">{!! trans('backend.ad.validated') !!}</span></a></li>
        <li @if (isset($adstatus) && $adstatus->title == 'expired') class="active" @endif><a href="{{ route('zxadmin.ad.status', 'expired') }}" class="text-orange"><i class="fa fa-hourglass-end"></i> <span class="hidden-xs hidden-sm">{!! trans('backend.ad.expired') !!}</span></a></li>
        <li @if (isset($adstatus) && $adstatus->title == 'banned') class="active" @endif><a href="{{ route('zxadmin.ad.status', 'banned') }}" class="text-red"><i class="fa fa-ban"></i> <span class="hidden-xs hidden-sm">{!! trans('backend.ad.banished') !!}</span></a></li>
        <li @if (isset($adstatus) && $adstatus->title == 'trashed') class="active" @endif><a href="{{ route('zxadmin.ad.status', 'trashed') }}" class="text-red"><i class="fa fa-trash"></i> <span class="hidden-xs hidden-sm">{!! trans('backend.ad.trashed') !!}</span></a></li>
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
            <div class="checkbox-auto-toggle">
              <table class="table table-hover table-striped" id="adList" @if (isset($adstatus))data-type="{{ $adstatus->title }}" @else data-type="all" @endif>
                <tbody>
                  @forelse ($ads as $ad)
                  <tr data-element-parent-action data-id="{{ $ad->id }}" data-title="{{ str_limit($ad->content->title, 20) }}">
                    <td class="vcenter col-md-1"><input type="checkbox"></td>
                    <td class="col-md-1">
                        <a href="{{ route('ad.preview', array($ad->id, str_slug($ad->content->title))) }}">
                        @if ($main_pic = $ad->photos()->main()->first())
                          <img src="{{ image_route('thumb', $main_pic->path) }}" class="img-responsive img-rounded">
                        @else
                          <i class="fa fa-picture-o" style="font-size:70px"></i>
                        @endif
                        </a>
                    </td>
                    <td class="vcenter col-md-9">
                      <div><a href="{{ route('ad.preview', array($ad->id, str_slug($ad->content->title))) }}">{{ $ad->content->title }}</a></div>
                      <div class="hidden-xs">{{ str_limit(strip_tags($ad->content->body, 50)) }}</div>
                      @if ($ad->adtype->is_headline)
                      <div><small class="label bg-orange"><i class="fa fa-star"></i> {!! trans('backend.ad.ad_headline') !!}</small></div>
                      @endif
                    </td>
                    <td class="vcenter col-md-1">
                      <div class="btn-group">
                        <button type="button" class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">
                          <i class="fa fa-cogs"></i> <span class="hidden-xs">{!! trans("backend.ad.moderate") !!}</span> &nbsp;<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right">
                          @if (!isset($adstatus) || $adstatus->title != 'validate')
                          <li><a href="javascript:void(0)" class="text-green" data-element-action = 'accept' data-element-action-text='{!! trans("backend.ad.validated_ad") !!}' data-element-action-route = '{{ route("zxadmin.ad.moderate", [$ad->id, "validate"]) }}'><i class="fa fa-check"></i> {!! trans('backend.ad.validate') !!}</a></li>
                          @endif
                          @if (!isset($adstatus) || $adstatus->title != 'banned')
                          <li>
                          <a href="#" class="text-orange" data-toggle="modal" data-target="#dialogBanAd" data-title="{{ $ad->content->title }}" data-element-action data-element-action-text='{!! trans("backend.ad.banished_ad") !!}' data-element-action-route = '{{ route("zxadmin.ad.moderate", [$ad->id, "banned"]) }}'><i class="fa fa-ban"></i> {!! trans('backend.ad.banish') !!}</a>
                          </li>
                          @endif
                          @if (!isset($adstatus) || $adstatus->title != 'pending')
                          <li><a href="javascript:void(0)" class="text-aqua" data-element-action = 'hold' data-element-action-text='{!! trans("backend.ad.pending_ad") !!}' data-element-action-route = '{{ route("zxadmin.ad.moderate", [$ad->id, "pending"]) }}'><i class="fa fa-hourglass-start"></i> {!! trans('backend.ad.place_a_hold_on') !!}</a></li>
                          @endif
                          <li class="divider"></li>
                          <li><a href="#" data-toggle="modal" data-target="#dialogAdtype" data-adtype="{{ $ad->adtype }}" data-ad-id = "{{ $ad->id }}"><i class="fa fa-cog"></i>{!! trans("backend.ad.personalize") !!}</a></li>
                          <li class="divider"></li>
                          <li><a href="{{ route('zxadmin.ad.edit', $ad->id) }}" class="text-blue"><i class="fa fa-edit"></i> {!! trans('backend.ad.edit') !!}</a></li>
                          <li>
                          <a href="#" class="text-red" data-element-action data-element-action-text='{!! trans("backend.ad.deleted_ad") !!}' data-element-action-route = '{{ route("zxadmin.ad.destroy", [$ad->id]) }}' data-toggle="modal" data-target="#confirmDeleteAction" data-title="{{ $ad->content->title }}" data-message="{!! trans('backend.ad.delete_ad_confirmation') !!}"><i class="fa fa-remove"></i> {!! trans('backend.ad.delete') !!}</a>
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                  @empty
                  <br />
                      <p class="text-center">{!! trans('backend.ad.empty_ads_text') !!}</p>
                  @endforelse
                </tbody>
              </table><!-- /.table -->
            </div><!-- /.mail-box-messages -->
          </div><!-- /.box-body -->
          @if (count($ads))
          <div class="box-footer no-padding">
            <div class="mailbox-controls">
              <!-- Check all button -->
              <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
              @if (!isset($adstatus) || $adstatus->title != 'validate')
              <button class="btn btn-success btn-sm" data-elements-action = 'accept' data-elements-action-text='{!! trans("backend.ad.js.nbr_validated_ads") !!}' data-elements-action-title='{!! trans("backend.ad.js.validation") !!}' data-elements-action-route = '{{ route("zxadmin.ad.moderate", ["_elements_", "validate"]) }}'><i class="fa fa-check"></i><span class="hidden-xs"> {!! trans('backend.ad.validate') !!}</span></button>
              @endif
              @if (!isset($adstatus) || $adstatus->title != 'banned')
              <span><button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#dialogBanAd" data-title="{!! trans('backend.ad.banish_many_ads') !!}" data-elements-action data-elements-action-text='{!! trans("backend.ad.js.nbr_banished_ads") !!}' data-elements-action-title='{!! trans("backend.ad.js.banishment") !!}' data-elements-action-route = '{{ route("zxadmin.ad.moderate", ["_elements_", "banned"]) }}'><i class="fa fa-ban"></i><span class="hidden-xs"> {!! trans('backend.ad.banish') !!}</span></button></span>
              @endif
              @if (!isset($adstatus) || $adstatus->title != 'pending')
              <button class="btn btn-info btn-sm" data-elements-action = 'hold' data-elements-action-text='{!! trans("backend.ad.js.nbr_hold_on_ads") !!}' data-elements-action-title='{!! trans("backend.ad.js.holding") !!}' data-elements-action-route = '{{ route("zxadmin.ad.moderate", ["_elements_", "pending"]) }}'><i class="fa fa-hourglass-start"></i><span class="hidden-xs"> {!! trans('backend.ad.place_a_hold_on') !!}</span></button>
              @endif
              <span><button class="btn btn-danger btn-sm" data-elements-action data-elements-action-text='{!! trans("backend.ad.js.nbr_deleted_ads") !!}' data-elements-action-title='{!! trans("backend.ad.js.suppression") !!}' data-elements-action-route = '{{ route("zxadmin.ad.destroy", ["_elements_"]) }}' data-toggle="modal" data-target="#confirmDeleteAction" data-title="{!! trans('backend.ad.delete_many_ads') !!}" data-message="{!! trans('backend.ad.delete_ads_confirmation') !!}"><i class="fa fa-remove"></i><span class="hidden-xs"> {!! trans('backend.ad.delete') !!}</span></button></span>
              <div class="pull-right">
                {!! with(new ZEDx\Utils\Pagination($ads->appends(['q' => Request::get('q')])))->render() !!}
              </div><!-- /.pull-right -->
            </div>
          </div>
          @endif

        </div><!-- /. box -->
      </div>
    </div><!-- /.nav-tabs-custom -->
  </div><!-- /.col -->
</div>
@include('backend::ad.modals.adtype')
@include('backend::ad.modals.banish')
@include('backend::ad.modals.delete')

@endsection
