@foreach ($themes as $themeName => $theme)
<div class="col-md-4">
  <div class="box box-primary">
    <div class="box-body">
      <img src="{{ route('zxadmin.theme.screenshot', $themeName) }}" alt="..." class="img-thumbnail img-responsive">
      <div class="caption">
        <h3>{{ $themeName }}</h3>
        <p><span class="label label-info"> {!! trans('backend.theme.author', ['author' => $theme['manifest']['author']]) !!}</span> | <span class="label label-primary">{!! trans('backend.theme.version', ['version' => $theme['manifest']['version']]) !!}</span></p>
        <br />
        <p>{{ $theme['manifest']['description'] }}</p>
        <br />
        <p><button class="btn btn-success theme-switch" data-name="{{ $themeName }}"><i class="fa fa-check"></i> {!! trans('backend.theme.activate') !!}</button> <a href="#" class="btn btn-danger pull-right"><i class="fa fa-remove"></i> {!! trans('backend.theme.delete') !!}</a></p>
      </div>
    </div>
  </div>
</div>
@endforeach
