<div class="form-group">
  {!! Form::label("field-type", trans("backend.field.field_type"), ['class' => 'label-text']) !!}
  {!! Form::select("type", [
    1 => trans('backend.field.input.selectbox_selectbox'),
    2 => trans('backend.field.input.selectbox_multipleSelect'),
    3 => trans('backend.field.input.multipleSelect_multipleSelect'),
    4 => trans('backend.field.input.inputNumeric'),
    5 => trans('backend.field.input.inputText_nothing'),
    //6 => 'input_date => input_date',
    //7 => 'input_date => date_range',
    //8 => 'date_range => date_range'
  ], null, ['id' => 'field-type', 'data-trans-ad' => trans('backend.field.ad'), 'data-trans-search_engine' => trans('backend.field.search_engine'), 'class' => 'select2 form-control']) !!}
</div>
