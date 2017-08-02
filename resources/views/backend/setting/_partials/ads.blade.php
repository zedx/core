<div class="row">
  <div class="col-md-6">
    <fieldset>
      <legend>{{ trans("backend.setting.ad.ad_content") }}</legend>
      <div class="form-group">
        {!! Form::label("ad_descr_min", trans("backend.setting.ad.ad_descr_min"), ['class' => 'col-sm-4 control-label label-text']) !!}
        <div class="col-sm-8">
        {!! Form::text("ad_descr_min", null, ['class' => 'form-control']) !!}
        <p class="note">{!! trans('backend.setting.ad.ad_descr_min_help') !!}</p>
        </div>
      </div>
      <div class="form-group">
        {!! Form::label("ad_descr_max", trans("backend.setting.ad.ad_descr_max"), ['class' => 'col-sm-4 control-label label-text']) !!}
        <div class="col-sm-8">
        {!! Form::text("ad_descr_max", null, ['class' => 'form-control']) !!}
        <p class="note">{!! trans('backend.setting.ad.ad_descr_max_help') !!}</p>
        </div>
      </div>
      <div class="form-group">
        {!! Form::label("currency", trans("backend.setting.ad.default_ad_currency"), ['class' => 'col-sm-4 control-label label-text']) !!}
        <div class="col-sm-8">
          <select class="form-control select2" name="default_ad_currency">
          @foreach ($currencies as $currency)
          <option @if ($currency == $setting->default_ad_currency) selected @endif value="{{ $currency }}"> {{ $currency }} </option>
          @endforeach
          </select>
          <p class="note">{!! trans('backend.setting.ad.default_ad_currency_help') !!}</p>
        </div>
      </div>
    </fieldset>
  </div>
  <div class="col-md-6">
    <fieldset>
        <legend>{{ trans("backend.setting.ad.watermark.watermark") }}</legend>
        <div class="form-group">
          {!! Form::label("watermark", trans("backend.setting.ad.watermark.image"), ['class' => 'col-sm-2 control-label label-text']) !!}
          <div class="col-sm-8 parent">
            <span class="image"><img src="{{ public_asset('uploads/watermark.png') }}" class="preview-logo"></span>
            <div class="btn btn-xs btn-info btn-file"><i class="fa fa-edit"></i> {{ trans("backend.setting.change") }} <input type="file" name="watermark" data-type="watermark" class="edit-image-setting"></div>
          </div>
        </div>
        <div class="form-group">
          {!! Form::label("image_settings[WATERMARK_SIZE_WIDTH]", trans("backend.setting.ad.watermark.size.width"), ['class' => 'col-sm-2 control-label label-text']) !!}
          <div class="col-sm-8">
          <div class="input-group">
              {!! Form::text("image_settings[WATERMARK_SIZE_WIDTH]", config("zedx.watermark.size.width"), ['class' => 'form-control']) !!}
              <div class="input-group-addon">
                px
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          {!! Form::label("image_settings[WATERMARK_SIZE_HEIGHT]", trans("backend.setting.ad.watermark.size.height"), ['class' => 'col-sm-2 control-label label-text']) !!}
          <div class="col-sm-8">
            <div class="input-group">
              {!! Form::text("image_settings[WATERMARK_SIZE_HEIGHT]", config("zedx.watermark.size.height"), ['class' => 'form-control']) !!}
              <div class="input-group-addon">
                px
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          {!! Form::label("image_settings[WATERMARK_POSITION]", trans("backend.setting.ad.watermark.position"), ['class' => 'col-sm-2 control-label label-text']) !!}
          <div class="col-sm-8">
            <select class="form-control select2" name="image_settings[WATERMARK_POSITION]">
            @foreach (['top-left', 'top', 'top-right', 'left', 'center', 'right', 'bottom-left', 'bottom', 'bottom-right'] as $position)
            <option @if ($position == config("zedx.watermark.position")) selected @endif value="{{ $position }}"> {{ trans('backend.setting.ad.watermark.positions.'.$position) }} </option>
            @endforeach
            </select>
          </div>
        </div>
    </fieldset>
  </div>
</div>
<fieldset>
    <legend>{{ trans("backend.setting.ad.image.images") }}</legend>
    <table class="table table-hover">
  <tr>
    <th></th>
    <th>{{ trans("backend.setting.ad.image.watermark") }}</th>
    <th>{{ trans("backend.setting.ad.image.resizeCanvas") }}</th>
    <th>{{ trans("backend.setting.ad.image.colorCanvas") }}</th>
    <th>{{ trans("backend.setting.ad.image.size.width") }}</th>
    <th>{{ trans("backend.setting.ad.image.size.height") }}</th>
  </tr>
  @foreach(['thumb', 'medium', 'large'] as $type)
  <tr>
    <td>{{ trans("backend.setting.ad.image." . $type) }}</td>
    <td>
      <input type="hidden" name="image_settings[{{ strtoupper($type) }}_WATERMARK]" value="0">
      <input type="checkbox" name="image_settings[{{ strtoupper($type) }}_WATERMARK]" value="1" class="minimal" @if(config("zedx.images.".$type.".watermark")) checked @endif>
    </td>
    <td>
    <input type="hidden" name="image_settings[{{ strtoupper($type) }}_RESIZECANVAS]" value="0">
      <input type="checkbox" name="image_settings[{{ strtoupper($type) }}_RESIZECANVAS]" value="1" class="minimal" @if(config("zedx.images.".$type.".resizeCanvas")) checked @endif>
    </td>
    <td class="col-md-2">
    <input type="text" class="form-control minicolors" name="image_settings[{{ strtoupper($type) }}_COLORCANVAS]" value="{{ config("zedx.images.".$type.".colorCanvas", 'fffff') }}" />
    </td>
    <td class="col-md-2">
      <div class="input-group">
        {!! Form::text("image_settings[".strtoupper($type)."_SIZE_WIDTH]", config("zedx.images.".$type.".size.width"), ['class' => 'form-control']) !!}
        <div class="input-group-addon">
          px
        </div>
      </div>
    </td>
    <td class="col-md-2">
      <div class="input-group">
        {!! Form::text("image_settings[".strtoupper($type)."_SIZE_HEIGHT]", config("zedx.images.".$type.".size.height"), ['class' => 'form-control']) !!}
        <div class="input-group-addon">
          px
        </div>
      </div>
    </td>
  </tr>
  @endforeach
</table>

</fieldset>
