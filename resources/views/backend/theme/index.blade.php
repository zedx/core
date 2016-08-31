@extends('backend::layout')
@section('page_header', trans("backend.theme.theme"))
@section('page_description', trans("backend.theme.theme_list"))
@section('page_right')
<a href="{{ route('zxadmin.theme.index') }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> <span class="hidden-xs">{!! trans('backend.theme.list') !!}</span></a>
<a href="{{ route('zxadmin.theme.add') }}" class="btn btn-success"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.theme.add') !!}</span></a>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body no-padding">
        <div class="checkbox-auto-toggle">
        @if (count($themes))
          <table class="table table-striped" id="themesTable" data-seturl="{{ route('zxadmin.theme.set') }}">
            <tr>
              <th></th>
              <th class="col-md-2">{!! trans("backend.theme.name") !!}</th>
              <th class="col-md-2">{!! trans("backend.theme.author") !!}</th>
              <th class="col-md-1">{!! trans("backend.theme.version") !!}</th>
              <th class="col-md-4">{!! trans("backend.theme.description") !!}</th>
              <th class="col-md-2">{!! trans("backend.theme.choosed_theme") !!}</th>
              <th class="col-md-1"></th>
            </tr>

            @foreach ($themes as $themeName => $theme)
            <tr>
              <td>
                @if (Themes::frontend()->getName() != $themeName)
                <input type="checkbox" class="flat-red" />
                @endif
              </td>

              <td>{{ $theme['manifest']['name'] }}</td>
              <td>{{ $theme['manifest']['author'] }}</td>
              <td>{{ $theme['manifest']['version'] }}</td>
              <td>{{ $theme['manifest']['description'] }}</td>
              <td>
              <input type="radio" class="flat-red theme-switch" name = "theme"  value = "{{ $themeName }}" {{ Themes::frontend()->getName() == $themeName ? 'checked' : ''}}>
              </td>
              <td class="pull-right">
                @if (Themes::frontend()->getName() == $themeName)
                  <button id="recompileThemeTemplates" data-url="{{ route('zxadmin.theme.recompile') }}" class="btn btn-primary btn-xs"><i class="fa fa-cogs"></i> {!! trans('backend.theme.recompile') !!}</button>
                @else
                <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i> {!! trans('backend.theme.delete') !!}</a>
                @endif
              </td>
            </tr>
            @endforeach
          </table>
          @else
            <br />
              <p class="text-center">{!! trans('backend.theme.empty_themes_text') !!}</p>
            <br />
          @endif
        </div>
      </div><!-- /.box-body -->
      @if (count($themes))
      <div class="box-footer no-padding">
        <div class="mailbox-controls">
          <!-- Check all button -->
          <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
          <button class="btn btn-danger btn-sm"><i class="fa fa-remove"></i> {!! trans('backend.theme.delete') !!}</button>
        </div>
      </div>
      @endif
    </div><!-- /.box -->
  </div>
</div>
@include('backend::theme.modals.delete')
@endsection
