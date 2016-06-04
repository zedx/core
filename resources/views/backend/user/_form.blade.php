<div class="box-body">
  <div class="row">
    <div class="col-md-6">
      <fieldset>
        <legend>{!! trans('Profil') !!}</legend>

        <div class="row">
          <div class="col-xs-6">
            <div class="form-group">
              {!! Form::label("email", trans("backend.user.email"), ['class' => 'label-text']) !!}
              {!! Form::text("email", null, ['class' => 'form-control']) !!}
            </div>
          </div>
          <div class="col-xs-3">
            <div class="form-group">
              {!! Form::label("password", trans("backend.user.password"), ['class' => 'label-text']) !!}
              <input class="form-control" name="password" type="password" id="password">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label("password_confirmation", trans("backend.user.password_confirmation"), ['class' => 'label-text']) !!}
              <input type="password" id="password_confirmation" class="form-control" name="password_confirmation">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xs-6">
            <div class="form-group">
              {!! Form::label("status", trans("backend.user.status"), ['class' => 'label-text']) !!}
              {!! Form::select("status", array(trans('backend.user.personal_account'), trans('backend.user.professional_account')), null, ['id' => 'status', 'class' => 'form-control']) !!}
            </div>
          </div>
          <div class="col-xs-6">
            <div class="form-group">
              {!! Form::label("name", trans("backend.user.name"), ['class' => 'label-text']) !!}
              {!! Form::text("name", null, ['class' => 'form-control']) !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xs-6">
            <div class="form-group">
              {!! Form::label("phone", trans("backend.user.phone_number"), ['class' => 'label-text']) !!}
              {!! Form::text("phone", null, ['class' => 'form-control']) !!}
            </div>
          </div>
          <div class="col-xs-6">
            <div class="form-group">
              {!! Form::label("is_phone", trans("backend.user.show_phone_number"), ['class' => 'label-text']) !!}
              {!! Form::select("is_phone", ['Non', 'Oui'], null, ['class' => 'form-control']) !!}
            </div>
          </div>
        </div>
        <fieldset id="professionnal" class="hide">
          <legend>{!! trans('backend.user.professional_account') !!}</legend>
          <div class="row">

            <div class="col-xs-6">
              <div class="form-group">
                {!! Form::label("company", trans("backend.user.corporate_name"), ['class' => 'label-text']) !!}
                {!! Form::text("company", null, ['class' => 'form-control']) !!}
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                {!! Form::label("siret", trans("backend.user.business_identification_number"), ['class' => 'label-text']) !!}
                {!! Form::text("siret", null, ['class' => 'form-control']) !!}
              </div>
            </div>
          </div>
        </fieldset>
      </fieldset>
    </div>
    <div class="col-md-6">
    <fieldset>
      <legend>{!! trans('Abonnement') !!}</legend>
      <div class="form-group">
        {!! Form::label("subscribed_at", trans("backend.user.subscription_date"), ['class' => 'label-text']) !!}
        <div class="input-group">
          {!! Form::text("subscribed_at", null, ['class' => 'form-control datepicker', 'data-date-format' => 'dd/mm/yyyy']) !!}
          <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        </div>
      </div>
      <div class="form-group">
        <label class="label-text" for="subscriptions">{!! trans("backend.user.subscription_type") !!}</label>
        <select class="form-control" id="subscriptions" name="subscription_id" data-default-adtypes = "{{ isset($user) ? $user->adtypes()->get(['title', 'adtype_id', 'number']) : $subscriptions->first()->adtypes()->get(['title', 'adtype_id', 'number']) }}">
        @foreach ($subscriptions as $subscription)
          <option value="{{ $subscription->id }}" data-adtypes="{{ $subscription->adtypes()->get(['title', 'adtype_id', 'number']) }}" {{ isset($user) && $user->subscription_id == $subscription->id ? "selected": "" }}>{{ $subscription->title }}</option>
        @endforeach
        </select>
      </div>
      <script type="x-tmpl-mustache" id="adtypesTemplate">
        @{{#.}}
          <div class="form-group">
            <label class="label-text">{!! trans("backend.user.nbr_ads") !!} @{{title}}</label>
            <input type="text" class="form-control" name="adtypes[@{{adtype_id}}][number]" placeholder="Votre code" value="@{{number}}">
          </div>
        @{{/.}}
      </script>
      <div id="adtypes"></div>
    </div>
    </fieldset>
  </div>
  @include('backend::errors.list')
</div>
<div class="box-footer">
  {!! Form::submit($submitButton, ["class" => "btn btn-primary pull-right"]) !!}
</div>
