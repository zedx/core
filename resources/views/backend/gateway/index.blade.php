@extends('backend::layout')
@section('page_header', trans("backend.gateway.gateway"))
@section('page_description', trans("backend.gateway.list"))
@section('page_right')
<a href="{{ route('zxadmin.gateway.index') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans('backend.gateway.list') !!}</span></a>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body no-padding">
        <table class="table table-striped">
        <tr>
          <th style="width: 10px"></th>
          <th class="col-md-10">{!! trans("backend.gateway.title") !!}</th>
          <th class="col-md-2">{!! trans("backend.gateway.is_enabled") !!}</th>
          <th style="width: 40px"></th>
        </tr>
        @foreach ($gateways as $gateway)
        <tr data-id="{{ $gateway->id }}" data-title="{{ str_limit($gateway->title, 20) }}">
          <td></td>
          <td class="col-md-10">{{ $gateway->title }}</td>
          <td class="col-md-2">
            <input class="gateway-switch-status bootstrap-switch" data-url="{{ route('zxadmin.gateway.switchStatus', [$gateway->id]) }}" data-size="mini" data-on-text="{!! trans("backend.gateway.yes") !!}" data-off-text="{!! trans("backend.gateway.no") !!}" data-off-color="danger" type="checkbox" {{ $gateway->enabled ? 'checked' : ''}}>
          </td>
          <td class="pull-right"><a href="{{ route('zxadmin.gateway.edit', $gateway->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> {!! trans('backend.gateway.configure') !!}</span></a></td>
        </tr>
        @endforeach
        </table>
      </div><!-- /.box-body -->
      @if (count($gateways))
      <div class="box-footer no-padding">
        <div class="pull-right">
            {!! with(new ZEDx\Utils\Pagination($gateways->appends(['q' => Request::get('q')])))->render() !!}
          </div><!-- /.pull-right -->
      </div>
      @endif
    </div><!-- /.box -->
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <div class="form-group">
          {!! Form::label("currency", trans("backend.setting.currency"), ['class' => 'col-sm-2 control-label label-text']) !!}
          <div class="col-sm-10">
            <select class="form-control select2" id="gateway-currency" data-url="{{ route('zxadmin.gateway.setCurrency') }}">
            @foreach ($currencies as $currency)
            <option @if ($currency == setting()->currency) selected @endif value="{{ $currency }}"> {{ $currency }} </option>
            @endforeach
            </select>
            <p class="note">{!! trans('backend.setting.currency_help') !!}</p>
          </div>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
</div>
@endsection
