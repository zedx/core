@extends('backend::layout')
@section('page_header', trans("backend.profile.profile"))
@section('page_description', trans("backend.profile.edit"))

@section('content')
<div class="panel panel-default">
  {!! Form::model($admin, ['method' => 'PATCH', 'route' => ['zxadmin.update']]) !!}
    <div class="panel-body">
      <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
        {!! Form::label("email", trans("backend.profile.email"), ['class' => 'label-text']) !!}
        {!! Form::text("email", null, ['class' => 'form-control']) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
      </div>
      <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
        {!! Form::label("name", trans("backend.profile.name"), ['class' => 'label-text']) !!}
        {!! Form::text("name", null, ['class' => 'form-control']) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
      </div>
      <hr>
      <div class="form-group {{ $errors->has('my_password') ? 'has-error' : ''}}">
        {!! Form::label("my_password", trans("backend.profile.current_password"), ['class' => 'label-text text-red']) !!}
          <input class="form-control" name="my_password" type="password" id="my_password">
          {!! $errors->first('my_password', '<p class="help-block">:message</p>') !!}
      </div>
      <hr>
      <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
        {!! Form::label("password", trans("backend.profile.password"), ['class' => 'label-text']) !!}
        <input class="form-control" name="password" type="password" id="password">
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
      </div>
      <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
        {!! Form::label("password_confirmation", trans("backend.profile.password_confirmation"), ['class' => 'label-text']) !!}
        <input type="password" id="password_confirmation" class="form-control" name="password_confirmation">
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
      </div>
      @if ($errors->has('demo_update_admin_profile'))
      <div class="alert alert-danger">
          {!! $errors->first('demo_update_admin_profile') !!}
      </div>
      @endif
    </div>
    <div class="panel-footer">
        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-refresh"></i> {!! trans('backend.profile.edit') !!}</button>
        <div class="clearfix"></div>
    </div>

  {!! Form::close() !!}

</div>
@endsection
