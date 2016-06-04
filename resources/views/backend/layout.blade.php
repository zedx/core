<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>ZEDx</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ public_asset(elixir_backend('css/styles.css')) }}" rel="stylesheet" type="text/css" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    @yield('css')

  </head>

  <body class="skin-blue sidebar-mini">
    <div class="wrapper">

      <!-- Main Header -->
      <header class="main-header">

        <!-- Logo -->
        <a href="{{ url("") }}" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><img src="{{ public_asset('build/backend/img/small-light-logo.png') }}" width="30" /></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img src="{{ public_asset('build/backend/img/light-logo.png') }}" width="150" /></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->
              <!-- /.access
              <li class="dropdown messages-menu">
                <a href="#">
                  <i class="fa fa-key"></i>
                </a>
              </li>
               -->
              @include('backend::update.menu')
               <li class="dropdown appearance-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                  <i class="fa fa-paint-brush"></i> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- start message -->
                        <a href="{{ route('zxadmin.theme.index') }}">
                          <div class="pull-left">
                            <i class="fa fa-photo text-blue"></i>
                          </div>
                          <h4>
                            {!! trans('backend.layout.themes') !!}
                          </h4>
                          <p>{!! trans('backend.layout.themes_description') !!}</p>
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('zxadmin.template.index') }}">
                          <div class="pull-left">
                            <i class="fa fa-file text-blue"></i>
                          </div>
                          <h4>
                            {!! trans('backend.layout.templates') !!}
                          </h4>
                          <p>{!! trans('backend.layout.templates_description') !!}</p>
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('zxadmin.menu.index') }}">
                          <div class="pull-left">
                            <i class="fa fa-list-alt text-blue"></i>
                          </div>
                          <h4>
                            {!! trans('backend.layout.menus') !!}
                          </h4>
                          <p>{!! trans('backend.layout.menus_description') !!}</p>
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('zxadmin.widget.index') }}">
                          <div class="pull-left">
                            <i class="fa fa-laptop text-blue"></i>
                          </div>
                          <h4>
                            {!! trans('backend.layout.widgets') !!}
                          </h4>
                          <p>{!! trans('backend.layout.widgets_description') !!}</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                </ul>
              </li>

              <li class="dropdown messages-menu">
                <!-- Menu toggle button -->
                <a href="{{ route('zxadmin.gateway.index') }}">
                  <i class="fa fa-bank"></i>
                </a>
              </li><!-- /.access -->

              <li class="dropdown messages-menu">
                <!-- Menu toggle button -->
                <a href="{{ route('zxadmin.setting.index') }}">
                  <i class="fa fa-wrench"></i>
                </a>
              </li><!-- /.access -->

              <li class="dropdown messages-menu">
                <!-- Menu toggle button -->
                <a href="{{ route('zxadmin.country.index') }}">
                  <i class="fa fa-globe"></i>
                </a>
              </li><!-- /.access -->

              <!-- Notifications Menu -->
              {{--*/ $numberOfNotifications = ZEDx\Models\Notification::visible()->notRead()->count() /*--}}
              <li class="dropdown notifications-menu">
                <!-- Menu toggle button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  @if ($numberOfNotifications)
                    <span id="notifications-number" class="label label-success">{{ $numberOfNotifications }}</span>
                  @endif
                </a>
                @include('backend::notification.menu', ['currency' => setting('currency')])
              </li>
              <li class="active">
                <!-- Menu Toggle Button -->
                <a href="{{ route('zxadmin.logout') }}" class="btn btn-sign-out btn-flat bg-red" role="button">
                  <i class="fa fa-sign-out icon-sign-out"></i> <span class="hidden-xs">{!! trans("backend.layout.disconnect") !!}</span>
                </a>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left ">
              <i class="fa fa-user icon-user"></i>
            </div>
            <div class="pull-left info">
              <p>{{ \Auth::guard('admin')->user()->name }}</p>

              <a href="{{ route('zxadmin.edit') }}"><i class="fa fa-circle text-success"></i> {!! trans("backend.layout.edit_profile") !!}</a>
            </div>
          </div>

          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">
            <li class="header">{{ strtoupper(trans("backend.layout.navigation")) }}</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="@if (Route::is('zxadmin.dashboard.*')) active @endif">
              <a href="{{ route('zxadmin.dashboard.index') }}"><i class='fa fa-dashboard'></i> <span>{!! trans('backend.layout.dashboard') !!}</span></a>
            </li>
            <li class="@if (Route::is('zxadmin.ad.*')) active @endif">
              <a href="{{ route('zxadmin.ad.status', 'pending') }}"><i class='fa fa-paper-plane-o'></i> <span>{!! trans('backend.layout.ads') !!}</span></a>
            </li>
            <li class="@if (Route::is('zxadmin.adtype.*')) active @endif">
              <a href="{{ route('zxadmin.adtype.index') }}"><i class='fa fa-cogs'></i> <span>{!! trans("backend.layout.ads_type") !!}</span></a>
            </li>
            <li class="@if (Route::is('zxadmin.user.*')) active @endif">
              <a href="{{ route('zxadmin.user.index') }}"><i class='fa fa-users'></i> <span>{!! trans('backend.layout.users') !!}</span></a>
            </li>
            <li class="@if (Route::is('zxadmin.category.*')) active @endif">
              <a href="{{ route('zxadmin.category.index') }}"><i class='fa fa-folder-open'></i> <span>{!! trans('backend.layout.categories') !!}</span></a>
            </li>
            <li class="@if (Route::is('zxadmin.field.*')) active @endif">
              <a href="{{ route('zxadmin.field.index') }}"><i class='fa fa-dot-circle-o'></i> <span>{!! trans('backend.layout.advanced_fields') !!}</span></a>
            </li>
            <li class="@if (Route::is('zxadmin.subscription.*')) active @endif">
              <a href="{{ route('zxadmin.subscription.index') }}"><i class='fa fa-shopping-cart'></i> <span>{!! trans('backend.layout.subscriptions') !!}</span></a>
            </li>
            <li class="@if (Route::is('zxadmin.page.*')) active @endif">
              <a href="{{ route('zxadmin.page.index') }}"><i class='fa fa-file'></i> <span>{!! trans('backend.layout.pages') !!}</span></a>
            </li>

            <li class="@if (Route::is('zxadmin.module.*')) active @endif">
              <a href="{{ route('zxadmin.module.index') }}"><i class='fa fa-th'></i> <span>{!! trans('backend.layout.modules') !!}</span></a>
            </li>
          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            @yield('page_header')
            <small class="hidden-xs">@yield('page_description')</small>
            <div class="pull-right">
            @yield('page_right')
            </div>
          </h1>


        </section>

        <!-- Main content -->
        <section class="content">

          @yield('content')

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <!-- Main Footer -->
      <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
          {!! trans('backend.layout.version', ['version' => \ZEDx\Core::VERSION]) !!}
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; {{ date("Y") }} <a href="https://zedx.io">ZEDx</a>.</strong> All rights reserved.
      </footer>

    </div><!-- ./wrapper -->

    <script src="{{ public_asset(elixir_backend('js/scripts.js')) }}"></script>
    @yield('script')

  </body>
</html>
