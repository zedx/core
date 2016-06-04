<div class="col-md-12">
  <fieldset>
    <legend>{!! trans('backend.field.search_engine') !!}</legend>
    <div class="row">
      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="form-group">
          {!! Form::label("search[min]", trans("backend.field.min"), ['class' => 'label-text']) !!}
          {!! Form::text("search[min]", null, ['class' => 'form-control', 'placeholder' => 'Ex : 0']) !!}
        </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="form-group">
          {!! Form::label("search[max]", trans("backend.field.max"), ['class' => 'label-text']) !!}
          {!! Form::text("search[max]", null, ['class' => 'form-control', 'placeholder' => 'Ex : 10000']) !!}
        </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="form-group">
          {!! Form::label("search[step]", trans("backend.field.step"), ['class' => 'label-text']) !!}
          {!! Form::text("search[step]", null, ['class' => 'form-control', 'placeholder' => 'Ex : 100']) !!}
        </div>
      </div>
    </div>
  </fieldset>
</div>
