<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ZEDx | Administration area</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="{{ public_asset(elixir_backend('css/styles.css')) }}" rel="stylesheet" type="text/css" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><img src="{{ public_asset('build/backend/img/dark-logo.png') }}" width="250" /></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        @yield('content')
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <script src="{{ public_asset(elixir_backend('js/scripts.js')) }}"></script>
  </body>
</html>
