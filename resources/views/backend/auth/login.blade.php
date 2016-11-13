@extends('backend::auth_layout')
@section('content')
<p class="login-box-msg">{{ trans('backend.login.please_login') }}</p>
  {!! Form::open(['route' => 'zxadmin.login', 'class' => 'form-horizontal']) !!}
  <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : ''}}">
      {!! Form::email("email", null, ['class' => 'form-control', 'placeholder' => trans('backend.login.email')]) !!}
      <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
  </div>

  <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : ''}}">
      {!! Form::password("password", ['class' => 'form-control', 'placeholder' => trans('backend.login.password')]) !!}
      <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
  </div>

  <div class="row">
    <div class="col-xs-8">
      <div class="checkbox icheck flat-red">
        <label>
          <input type="checkbox" name="remember"> {{ trans('backend.login.remember_me') }}
        </label>
      </div>
    </div><!-- /.col -->
    <div class="col-xs-4">
      <button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-sign-in"></i> <span class="hidden-xs">{{ trans('backend.login.log_in') }}</span></button>
    </div><!-- /.col -->
  </div>
</form>
<br />
<a href="{{ route('zxadmin.password.email') }}" class="pull-right">{{ trans('backend.login.forgot_password') }}</a><br />
@endsection
