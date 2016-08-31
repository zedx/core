@extends('backend::layout')
@section('page_header', trans("backend.ad.ad"))
@section('page_description', trans("backend.ad.choose_ad_type"))
@section('page_right')
<a href="{{ route('zxadmin.ad.status', 'pending') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans('backend.ad.list') !!}</span></a>
<a href="{{ route('zxadmin.ad.choose') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.ad.add') !!}</span></a>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
      <div class="box-body no-padding">
        <table class="table reverseTable">
          <tr>
            <td>&nbsp;</td>
            <td>{!! trans('backend.adtype.headline_ad') !!}</td>
            <td>{!! trans('backend.adtype.renew_an_ad') !!}</td>
            <td>{!! trans('backend.adtype.edit_an_ad') !!}</td>

            <td>{!! trans('backend.adtype.add_photos') !!}</td>
            <td>{!! trans('backend.adtype.photos_peer_ad') !!}</td>
            <td>{!! trans('backend.adtype.update_photos') !!}</td>

            <td>{!! trans('backend.adtype.add_videos') !!}</td>
            <td>{!! trans('backend.adtype.videos_peer_ad') !!}</td>
            <td>{!! trans('backend.adtype.update_videos') !!}</td>

            <td>{!! trans('backend.adtype.display_time_of_an_ad') !!}</td>
            <td class="vcenter">{!! trans('backend.adtype.ad_price') !!}</td>
            <td>&nbsp;</td>
          </tr>
          @foreach ($adtypes as $adtype)
          <tr>
            <td><h4>{{ $adtype->title }}</h4></td>
            <td>@if ($adtype->is_headline) <i class="fa fa-check-circle-o fa-lg text-green"></i> @else <i class="fa fa-times-circle-o fa-lg text-red"></i> @endif</td>
            <td>@if ($adtype->can_renew) <i class="fa fa-check-circle-o fa-lg text-green"></i> @else <i class="fa fa-times-circle-o fa-lg text-red"></i> @endif</td>
            <td>@if ($adtype->can_edit) <i class="fa fa-check-circle-o fa-lg text-green"></i> @else <i class="fa fa-times-circle-o fa-lg text-red"></i> @endif</td>
            <td>@if ($adtype->can_add_pic) <i class="fa fa-check-circle-o fa-lg text-green"></i> @else <i class="fa fa-times-circle-o fa-lg text-red"></i> @endif</td>

            <td>@if ($adtype->nbr_pic >= 9999) <small class="label bg-green">{{ mb_strtoupper(trans('backend.adtype.unlimited')) }}</small> @elseif ($adtype->nbr_pic == 0) <i class="fa fa-times-circle-o fa-lg text-red"></i> @else <b>{{ $adtype->nbr_pic }}</b> @endif</td>

            <td>@if ($adtype->can_update_pic) <i class="fa fa-check-circle-o fa-lg text-green"></i> @else <i class="fa fa-times-circle-o fa-lg text-red"></i> @endif</td>
            <td>@if ($adtype->can_add_video) <i class="fa fa-check-circle-o fa-lg text-green"></i> @else <i class="fa fa-times-circle-o fa-lg text-red"></i> @endif</td>

            <td>@if ($adtype->nbr_video >= 9999) <small class="label bg-green">{{ mb_strtoupper(trans('backend.adtype.unlimited')) }}</small> @elseif ($adtype->nbr_video == 0) <i class="fa fa-times-circle-o fa-lg text-red"></i> @else <b>{{ $adtype->nbr_video }}</b> @endif</td>

            <td>@if ($adtype->can_update_video) <i class="fa fa-check-circle-o fa-lg text-green"></i> @else <i class="fa fa-times-circle-o fa-lg text-red"></i> @endif</td>

            <td>@if ($adtype->nbr_days >= 9999) <small class="label bg-green">{{ mb_strtoupper(trans('backend.adtype.unlimited')) }}</small> @elseif ($adtype->nbr_days == 0) <i class="fa fa-times-circle-o fa-lg text-red"></i> @else <b><span class="text-info">{{ trans_choice('backend.adtype.nbr_days', $adtype->nbr_days)}}</span></b> @endif</td>

            <td>@if ($adtype->price > 0) <h3>{{$adtype->price}}</h3> @else <h3 class="text-green">{{ mb_strtoupper(trans('backend.adtype.free')) }}</h3> @endif</td>
            <td><a href="{!! route('zxadmin.ad.create', $adtype->id) !!}" class="btn btn-primary"><i class="fa fa-plus"></i> {!! trans('backend.adtype.add') !!}</a></td>
          </tr>
          @endforeach
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
	</div>
</div>
@endsection
