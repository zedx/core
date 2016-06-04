@extends('backend::auth_layout')
@section('content')
<p class="login-box-msg">{{ trans('backend.password_reset.reset_password') }}</p>
@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<form class="form-horizontal" role="form" method="POST" action="{{ route('zxadmin.password.email') }}">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">{!! trans('backend.password_reset.email_address') !!}</label>

        <div class="col-md-8">
            <input type="text" class="form-control" name="email" value="{{ old('email') }}">

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-btn fa-envelope"></i> {!! trans('backend.password_reset.send_reset_link') !!}
            </button>
        </div>
    </div>
</form>
<br />
<a href="{{ route('zxadmin.login') }}" class="pull-right">{{ trans('backend.login.log_in') }}</a><br />
@endsection
