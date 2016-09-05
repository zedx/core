@extends('backend::layout')
@section('page_header', trans("backend.map.map"))
@section('page_description', trans("backend.map.country_list"))
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
      <div class="box-header">
        <form action="{{ Request::url() }}" >
          <div class="input-group">
            <input type="text" name="q" class="form-control input-sm pull-right" value="{{ Request::get('q') }}" />
            <div class="input-group-btn">
              <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </form>
      </div><!-- /.box-header -->
      <div class="box-body no-padding">
        <table class="table table-striped">
          <tr>
            <th>{!! trans('backend.map.flag') !!}</th>
            <th>{!! trans('backend.map.code') !!}</th>
            <th>{!! trans('backend.map.country') !!}</th>
            <th>{!! trans('backend.map.currency') !!}</th>
            <th>{!! trans('backend.map.currency_symbole') !!}</th>
            <th>{!! trans('backend.map.available') !!}</th>
            <th>{!! trans('backend.map.activated') !!}</th>
            <th style="width: 40px"></th>
            <th style="width: 40px"></th>
          </tr>
          @foreach ($countries as $country)
          <tr>
            <td><i class="flag-icon flag-icon-{{ strtolower($country->code) }}"></i></td>
            <td>{{ $country->code }}</td>
            <td>{{ $country->en }}</td>
            <td>{{ $country->currency }}</td>
            <td><span class="map-symbole" data-url="{{ route('zxadmin.country.update.symbole', $country->id) }}">{{ $country->currency_symbole }}</span></td>
            <td>
              @if (Maps::exists($country->code))
              <i class="fa fa-check text-green"></i>
              @else
              <i class="fa fa-remove text-red"></i>
              @endif
            </td>
            <td>
              @if (Maps::exists($country->code))
              <input type="checkbox" class="map-swap" {{ $country->is_activate == 1 ? 'checked':'' }} value="1" data-id="{{ $country->id }}" data-url="{{ route('zxadmin.country.swap', $country->id) }}">
              @else
              <i class="fa fa-remove text-red"></i>
              @endif
            </td>
            <td>
              @if (Maps::exists($country->code))
            <button class="btn btn-xs btn-primary edit-map" {{ Maps::constructAttributes($country->code) }} data-toggle="modal" data-code="{{ strtolower($country->code) }}" data-target="#dialog-edit" data-url="{{ route('zxadmin.country.personalize', $country->id) }}"><i class="fa fa-edit"></i> {!! trans('backend.map.personalize') !!}</button>
            @else
              <i class="fa fa-remove text-red"></i>
            @endif
            </td>
            <td><button class="btn btn-xs btn-success upload-map" data-url="#" data-code="{{ strtolower($country->code) }}" data-toggle="modal" data-target="#dialog-upload"><i class="fa fa-upload"></i> {!! trans('backend.map.upload') !!}</button></td>
          </tr>
          @endforeach

        </table>
      </div><!-- /.box-body -->
      @if (count($countries))
      <div class="box-footer clearfix">
        {!! with(new ZEDx\Utils\Pagination($countries->appends(['q' => Request::get('q')])))->render() !!}
      </div>
      @endif
    </div><!-- /.box -->
  </div>
</div>
@include('backend::country.modals.edit')
@include('backend::country.modals.upload')
@endsection
