<div class="box-body">
  <div class="row">
    <div class="col-md-6">
    <fieldset>
    <legend>{!! trans('backend.subscription.subscription') !!}</legend>
        <div class="form-group">
           {!! Form::label("title", trans("backend.subscription.title"), ['class' => 'label-text']) !!}
           {!! Form::text("title", null, ['class' => 'form-control', 'placeholder' => 'Ex : Basic']) !!}
        </div>
        <div class="form-group">
          {!! Form::label("description", trans("backend.subscription.description"), ['class' => 'label-text']) !!}
          {!! Form::text("description", null, ['class' => 'form-control', 'placeholder' => 'Ex : Basic']) !!}
        </div>
        <div class="form-group">
          {!! Form::label("days", trans("backend.subscription.display_time"), ['class' => 'label-text']) !!}
          <span data-toggle="tooltip" title="" style="margin-right: 5px;" data-original-title="{!! trans('backend.subscription.display_time_help') !!}"><i class="fa fa-question-circle"></i></span>
          <div class="input-group">
            {!! Form::text("days", null, ['class' => 'form-control', 'placeholder' => 'Ex : 60']) !!}
            <div class="input-group-addon">
              {!! trans('backend.subscription.days') !!}
            </div>
          </div>
        </div>
        <div class="form-group">
          {!! Form::label("price", trans("backend.subscription.price"), ['class' => 'label-text']) !!}
          <div class="input-group">
            {!! Form::text("price", null, ['class' => 'form-control', 'placeholder' => 'Ex : 12']) !!}
            <div class="input-group-addon">
              {{ setting('currency') }}
            </div>
          </div>
        </div>
      </fieldset>
    </div>
    <div class="col-md-6">
      <fieldset>
      <legend>{!! trans("backend.subscription.ad_types") !!}</legend>
      @foreach(\ZEDx\Models\Adtype::all() as $adtype)
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::label("adtypes[$adtype->id][enabled]", trans("backend.subscription.ad_ad_type", ['ad_type_title' => $adtype->title]), ['class' => 'label-text']) !!}
            {!! Form::select("adtypes[$adtype->id][enabled]", array(trans('backend.subscription.no'), trans('backend.subscription.yes')), isset($adtype) && isset($adtypes[$adtype->id]) && $adtypes[$adtype->id] > 0 ? 1:0, ['class' => 'form-control selectSubscriptionAdType', 'data-id' => $adtype->id]) !!}
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group @if(isset($adtype) && (!isset($adtypes[$adtype->id]) || $adtypes[$adtype->id] < 1)) hide @endif" id="nbrSubscriptionAdType_{{ $adtype->id }}">
            {!! Form::label("adtypes[$adtype->id][number]", trans("backend.subscription.nbr_ads"), ['class' => 'label-text']) !!}
            {!! Form::text("adtypes[$adtype->id][number]", isset($adtype) && isset($adtypes[$adtype->id]) ? $adtypes[$adtype->id] : 0, ['class' => 'form-control']) !!}
          </div>
        </div>
      </div>
      @endforeach
      </fieldset>
    </div>
  </div>
  @include ('backend::errors.list')
</div>
<div class="box-footer">
  {!! Form::submit($submitButton, ["class" => "btn btn-primary pull-right"]) !!}
</div>
