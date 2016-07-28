<div class="box-body">
  <div class="row">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">
          <div class="form-group">
            {!! Form::label("name", trans("backend.field.field_name"), ['class' => 'label-text']) !!}
            {!! Form::text("name", null, ['class' => 'form-control', 'placeholder' => 'Ex : Prix']) !!}
          </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
          <div class="form-group">
            {!! Form::label("unit", trans("backend.field.unit"), ['class' => 'label-text']) !!}
            <span data-toggle="tooltip" title="" style="margin-right: 5px;" data-original-title="{!! trans('backend.field.unit_help') !!}"><i class="fa fa-question-circle"></i></span>
            {!! Form::text("unit", null, ['class' => 'form-control', 'placeholder' => 'Ex : m2']) !!}
          </div>
        </div>
     </div>
     <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">
          <div class="form-group">
            {!! Form::label("title", trans("backend.field.title"), ['class' => 'label-text']) !!}
            {!! Form::text("title", null, ['class' => 'form-control', 'placeholder' => 'Ex : Prix immobilier']) !!}
          </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
          <div class="form-group">
            {!! Form::label("is_price", trans("backend.field.price_field"), ['class' => 'label-text']) !!}
            <span data-toggle="tooltip" title="" style="margin-right: 5px;" data-original-title="{!! trans('backend.field.price_field_help') !!}"><i class="fa fa-question-circle"></i></span>
            {!! Form::select("is_price", array(trans('backend.field.no'), trans('backend.field.yes')), null, ['class' => 'form-control']) !!}
          </div>
        </div>
     </div>
     <fieldset>
          <legend>{!! trans('backend.field.visibility') !!}</legend>
          <div class="row">
            <div class="col-xs-12">
              <div class="checkbox">
                <label>
                <input type="hidden" name="is_in_ads_list" value="0">
                  {!! Form::checkbox('is_in_ads_list', 1, null) !!} {!! trans('backend.field.visible_in_ads_list') !!}
                </label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="checkbox">
                <label>
                <input type="hidden" name="is_in_ad" value="0">
                  {!! Form::checkbox('is_in_ad', 1, null) !!} {!! trans('backend.field.visible_in_ad') !!}
                </label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="checkbox">
                <label>
                  <input type="hidden" name="is_in_search" value="0">
                  {!! Form::checkbox('is_in_search', 1, null) !!} {!! trans('backend.field.visible_in_search_engine') !!}
                </label>
              </div>
            </div>
          </div>
        </fieldset>
    </div>
    <div class="col-md-6">
      @include ('backend::field._partials.type')
      <div id="fieldController" class="form-group">
        <div id="configInputNumeric" class="row {{ isset($field) &&  $field->type == 3 ? '' : 'hide'}}">
          @include ('backend::field._partials.format')
          @include ('backend::field._partials.search')
        </div>
        <div id = "options" class="hide" data-options = "{{ isset($options) ? json_encode($options) : '[]' }}">
          @include ('backend::field._partials.options')
        </div>
      </div>
    </div>
  </div>
  @include ('backend::errors.list')
</div>
<div class="box-footer">
  {!! Form::submit($submitButton, ["class" => "btn btn-primary pull-right"]) !!}
</div>
