@extends('backend::layout')
@section('page_header', trans("backend.firewall.firewall"))
@section('page_description', trans("backend.firewall.website_access"))
@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">{!! trans("backend.firewall.deny_access_to_ip") !!}</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        {!! Form::open(['route' => 'zxadmin.firewall.store']) !!}
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-laptop"></i>
            </div>
            <input type="text" name="ip" class="form-control"/>
            <div class="input-group-btn">
              <button id="add-new-event" type="submit" class="btn btn-danger btn-flat"><i class="fa fa-ban"></i> {!! trans("backend.firewall.deny_ip") !!}</button>
            </div><!-- /btn-group -->
          </div><!-- /input-group -->
        </div>
        <input type="hidden" name="type" value="blacklist" />
        {!! Form::close() !!}
      </div>
      <div class="box-header">
        <h3 class="box-title">{!! trans("backend.firewall.list_denied_ip") !!}</h3>
      </div><!-- /.box-header -->
      <div class="box-body no-padding">
        <table class="table table-striped">
        @foreach ($blacklist as $blocked)
          <tr>
            <td>{{ $blocked }}</td>
            <td>
            {!! Form::open(array('class' => 'form-inline', 'method' => 'DELETE', 'route' => array('zxadmin.firewall.destroy', base64_encode($blocked)))) !!}
              <input type="hidden" name="type" value="blacklist" />
              <button type="submit" class="btn btn-xs btn-danger pull-right"><i class="fa fa-remove"></i></button>
            {!! Form::close() !!}
            </td>
          </tr>
        @endforeach
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">{!! trans("backend.firewall.allow_access_to_ip") !!}</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        {!! Form::open(['route' => 'zxadmin.firewall.store']) !!}
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-laptop"></i>
            </div>
            <input type="text" name="ip" class="form-control"/>
            <div class="input-group-btn">
              <button id="add-new-event" type="submit" class="btn btn-success btn-flat"><i class="fa fa-check"></i> {!! trans("backend.firewall.allow_ip") !!}</button>
            </div><!-- /btn-group -->
          </div><!-- /input-group -->
        </div>
        <input type="hidden" name="type" value="whitelist" />
        {!! Form::close() !!}
      </div>
      <div class="box-header">
        <h3 class="box-title">{!! trans("backend.firewall.list_allowed_ip") !!}</h3>
      </div><!-- /.box-header -->
      <div class="box-body no-padding">
        <table class="table table-striped">
        @foreach ($whitelist as $allowed)
          <tr>
            <td>{{ $allowed }}</td>
            <td>
            {!! Form::open(array('class' => 'form-inline', 'method' => 'DELETE', 'route' => array('zxadmin.firewall.destroy', base64_encode($allowed)))) !!}
              <input type="hidden" name="type" value="whitelist" />
              <button type="submit" class="btn btn-xs btn-danger pull-right"><i class="fa fa-remove"></i></button>
            {!! Form::close() !!}
            </td>
          </tr>
        @endforeach
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
</div>
@endsection
