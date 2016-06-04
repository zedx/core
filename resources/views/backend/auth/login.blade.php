@extends('backend::auth_layout')
@section('content')
<p class="login-box-msg">{{ trans('backend.login.please_login') }}</p>
<form class="form-horizontal" role="form" method="POST" action="{{ route('zxadmin.login') }}">
{!! csrf_field() !!}
  <div class="form-group has-feedback">
    <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ trans('backend.login.email') }}">
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
  </div>
  <div class="form-group has-feedback">
    <input type="password" class="form-control" name="password" placeholder="{{ trans('backend.login.password') }}">
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
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
