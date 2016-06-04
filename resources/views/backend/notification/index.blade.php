@extends('backend::layout')
@section('page_header', trans("backend.notification.notification"))
@section('page_description', trans("backend.notification.notification_list"))
@section('page_right')
<div class="hidden-xs hidden-sm notification-daterange input-group">
  <div class="input-group-addon">
    <i class="fa fa-calendar"></i>
  </div>
    <input type="text" name="range" data-url="{{ Request::url() }}" class="notificationDaterange form-control pull-right" value="{{ Request::get('dateFrom', '12/29/2015') }} - {{ Request::get('dateTo', Carbon\Carbon::now()->format('m/d/Y')) }}">
</div>
@endsection

@section('content')
  <div class="visible-xs visible-sm row">
    <div class="col-md-12">
      <div class="input-group">
        <div class="input-group-addon">
          <i class="fa fa-calendar"></i>
        </div>
        <input type="text" name="range" data-url="{{ Request::url() }}" class="notificationDaterange form-control pull-right" value="{{ Request::get('dateFrom') }} - {{ Request::get('dateTo') }}">
      </div>
    </div>
  </div>
  <br >
  <div class="row" id="notifications">
  @if ($notifications->count())
    <div class="col-md-12">
      <ul class="timeline" id="notificationItems" data-ajax-loader="{{ url('public/build/backend/img/loader.gif') }}">
        @foreach ($notifications as $notification)
          @if ($notification->actor_role == 'root')
          {{--*/ $actorName = "<a href='" . route("zxadmin.admin.edit", $notification->actor_id) . "' class='text-red'><i class='fa fa-user-secret'></i> " . $notification->actor_name . "</a>" /*--}}
          @elseif ($notification->actor_role == 'user')
          {{--*/ $actorName = "<a href='" . route("zxadmin.user.edit", $notification->actor_id) . "'><i class='fa fa-user'></i> " . $notification->actor_name . "</a>" /*--}}
          @elseif ($notification->actor_role == 'system')
          {{--*/ $actorName = "<a href='#' class = 'text-green'>[ " . $notification->actor_name . " ]</a>" /*--}}
          @endif

          @include("backend::notification._partials.timeline.{$notification->type}", ['actorName' => $actorName])
        @endforeach

      </ul>
    </div>
    <div class="col-md-12 text-center">
      {!! $notifications->appends(['dateFrom' => Request::get('dateFrom'), 'dateTo' => Request::get('dateTo')])->render() !!}
    </div>
  @else
    <div class="col-md-12">
      <div class="callout callout-info">
        <i class="icon fa fa-info"></i> {!! trans('backend.notification.empty_notifications_text') !!}
      </div>
    </div>
  @endif
  </div>
@endsection
