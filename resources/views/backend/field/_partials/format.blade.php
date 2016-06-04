<div class="col-md-12">
  <div class="form-group">
    {!! Form::label("is_format", trans("backend.field.format"), ['class' => 'label-text']) !!}
    <span  data-toggle="tooltip" title="" style="margin-right: 5px;" data-original-title="{!! trans('backend.field.format_help', ['number' => '1000000', 'number_format' => number_format('1000000', trans('backend.format.number.decimals') , trans('backend.format.number.dec_point'), trans('backend.format.number.thousands_sep'))]) !!}"><i class="fa fa-question-circle"></i></span>
    {!! Form::select("is_format", array(trans('backend.field.no'), trans('backend.field.yes')), null, ['class' => 'form-control']) !!}
  </div>
</div>
