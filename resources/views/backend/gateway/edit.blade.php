@extends('backend::layout')
@section('page_header', trans("backend.gateway.gateway"))
@section('page_description', ucfirst($gateway->name))
@section('page_right')
<a href="{{ route('zxadmin.gateway.index') }}" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs">{!! trans('backend.gateway.list') !!}</span></a>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">
        <h4>{!! trans('backend.gateway.setting') !!}</h4>
      </div><!-- /.box-header -->
      {!! Form::model($gateway, ['method' => 'PATCH', 'route' => ['zxadmin.gateway.update', $gateway->id]]) !!}
      <div class="box-body">
        <div class="row">
          <div class="col-md-offset-3 col-md-6">
            @foreach($attributes as $attribute => $value)
            <div class="form-group">
              <label for="{{ $attribute }}" class="label-text">{{ $attribute }}</label>
              @if (is_bool($value))
              <select class="form-control" name="options[{{ $attribute }}]" id="{{ $attribute }}">
                <option @if (!$value) selected @endif value="0">{!! trans('backend.gateway.no') !!}</option>
                <option @if ($value) selected @endif value="1">{!! trans('backend.gateway.yes') !!}</option>
              </select>
              @else
              <input class="form-control" name="options[{{ $attribute }}]" type="text" value="{{ $value }}" id="{{ $attribute }}">
              @endif
            </div>
            @endforeach
          </div>
        </div>
        @include ('backend::errors.list')
      </div>
      <div class="box-footer">
        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-wrench"></i> {!! trans("backend.gateway.configure") !!}</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
@if ($gateway->help != '')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">
        <h4>{!! trans('backend.gateway.help') !!}</h4>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            {!! $gateway->help !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endif
@endsection
