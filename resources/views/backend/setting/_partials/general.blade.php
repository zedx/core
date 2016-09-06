<div class="form-group">
  {!! Form::label("logo", trans("backend.setting.logo"), ['class' => 'col-sm-2 control-label label-text']) !!}
  <div class="col-sm-8 parent">
    <span class="image"><img src="{{ public_asset('logo.png') }}" class="preview-logo"></span>
    <div class="btn btn-xs btn-info btn-file"><i class="fa fa-edit"></i> {{ trans("backend.setting.change") }} <input type="file" name="logo" data-type="logo" class="edit-image-setting"></div>
  </div>
</div>
<div class="form-group">
  {!! Form::label("website_name", trans("backend.setting.website_name"), ['class' => 'col-sm-2 control-label label-text']) !!}
  <div class="col-sm-10">
  {!! Form::text("website_name", null, ['class' => 'form-control']) !!}
  </div>
</div>
<div class="form-group">
  {!! Form::label("website_url", trans("backend.setting.website_url"), ['class' => 'col-sm-2 control-label label-text']) !!}
  <div class="col-sm-10">
  {!! Form::text("website_url", null, ['class' => 'form-control']) !!}
  </div>
</div>
<div class="form-group">
  {!! Form::label("website_title", trans("backend.setting.website_title"), ['class' => 'col-sm-2 control-label label-text']) !!}
  <div class="col-sm-10">
  {!! Form::text("website_title", null, ['class' => 'form-control']) !!}
  </div>
</div>
<div class="form-group">
  {!! Form::label("website_description", trans("backend.setting.website_description"), ['class' => 'col-sm-2 control-label label-text']) !!}
  <div class="col-sm-10">
  {!! Form::textarea("website_description", null, ['class' => 'form-control']) !!}
  </div>
</div>

<div class="form-group">
  {!! Form::label("language", trans("backend.setting.default_lng"), ['class' => 'col-sm-2 control-label label-text']) !!}
  <div class="col-sm-10">
  {!! Form::select("language", $languages, $setting->language, ['class' => 'form-control select2']) !!}
  </div>
</div>
<div class="form-group">
  {!! Form::label("website_tracking_code", trans("backend.setting.website_tracking_code"), ['class' => 'col-sm-2 control-label label-text']) !!}
  <div class="col-sm-10">
  {!! Form::textarea("website_tracking_code", null, ['class' => 'form-control']) !!}
  <p class="note">{!! trans('backend.setting.website_tracking_code_help') !!}</p>
  </div>
</div>
