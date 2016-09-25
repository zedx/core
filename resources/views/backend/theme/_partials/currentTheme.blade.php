<div class="row">
  <div class="col-md-4"><img src="{{ route('zxadmin.theme.screenshot', $currentTheme['manifest']['name']) }}" class="img-responsive img-thumbnail"></div>
  <div class="col-md-8">
    <h2>{{ $currentTheme['manifest']['name'] }}</h2>
    <span class="pull-right small label label-success"><i class="fa fa-check"></i> {!! trans('backend.theme.choosed_theme') !!}</span>
    <p><span class="label label-info">{!! trans('backend.theme.author', ['author' => $currentTheme['manifest']['author']]) !!}</span> | <span class="label label-primary">{!! trans('backend.theme.version', ['version' => $currentTheme['manifest']['version']]) !!}</span></p>
    <br >
    <div>{{ $currentTheme['manifest']['description'] }}</div>
    <br /><br />
    <div class="row">
      <div class="col-md-4">
        <a href="#" class="btn btn-success btn-block"><i class="fa fa-edit"></i> {!! trans('backend.template.personalize') !!}</a>
      </div>
      <div class="col-md-4">
        <button id="recompileThemeTemplates" data-url="{{ route('zxadmin.theme.recompile') }}" class="btn btn-primary btn-block"><i class="recompile-icon fa fa-cogs"></i> {!! trans('backend.theme.recompile') !!}</button>
      </div>
    </div>
  </div>
</div>
