<div class="box-body">
  <div class="row">
    <div class="col-md-5">
      <div class="form-group">
        {!! Form::label("title", trans("backend.adtype.title"), ['class' => 'label-text']) !!} {!! Form::text("title", null, ['class' => 'form-control', 'placeholder' => 'Ex : Basic']) !!}
      </div>
      <div class="form-group">
        {!! Form::label("nbr_days", trans('backend.adtype.display_time'), ['class' => 'label-text']) !!}
        <span data-toggle="tooltip" title="" style="margin-right: 5px;" data-original-title="{!! trans('backend.adtype.display_time_help') !!}"><i class="fa fa-question-circle"></i></span>
        <div class="input-group">
          {!! Form::text("nbr_days", null, ['class' => 'form-control', 'placeholder' => 'Ex : 60']) !!}
          <div class="input-group-addon">
            <i>{!! trans('backend.adtype.days') !!}</i>
          </div>
        </div>
      </div>
      <div class="form-group">
        {!! Form::label("price", trans("backend.adtype.price"), ['class' => 'label-text']) !!}
        <div class="input-group">
          {!! Form::text("price", null, ['class' => 'form-control', 'placeholder' => 'Ex : 15']) !!}
          <div class="input-group-addon">
            <i>{{ setting('currency') }}</i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-7">
      <div class="row">
        <div class="col-xs-4">
          <div class="form-group">
            {!! Form::label("is_headline", trans("backend.adtype.headline_ad"), ['class' => 'label-text']) !!} {!! Form::select("is_headline", array(trans('backend.adtype.no'), trans('backend.adtype.yes')), null, ['class' => 'form-control']) !!}
          </div>
        </div>
        <div class="col-xs-4">
          <div class="form-group">
            {!! Form::label("can_renew", trans("backend.adtype.renew_an_ad"), ['class' => 'label-text']) !!} {!! Form::select("can_renew", array(trans('backend.adtype.no'), trans('backend.adtype.yes')), null, ['class' => 'form-control']) !!}
          </div>
        </div>
        <div class="col-xs-4">
          <div class="form-group">
            {!! Form::label("can_edit", trans("backend.adtype.edit_an_ad"), ['class' => 'label-text']) !!} {!! Form::select("can_edit", array(trans('backend.adtype.no'), trans('backend.adtype.yes')), null, ['class' => 'form-control']) !!}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-4">
          <div class="form-group">
            {!! Form::label("can_add_pic", trans("backend.adtype.add_photos"), ['class' => 'label-text']) !!} {!! Form::select("can_add_pic", array(trans('backend.adtype.no'), trans('backend.adtype.yes')), null, ['class' => 'form-control']) !!}
          </div>
        </div>
        <div class="col-xs-4">
          <div class="form-group @if (!isset($adtype) || !$adtype->can_add_pic) hide @endif" data-check-type = 'photo'>
            {!! Form::label("nbr_pic", trans("backend.adtype.photos_peer_ad"), ['class' => 'label-text']) !!} {!! Form::text("nbr_pic", null, ['class' => 'form-control', 'placeholder' => 'Ex : 5']) !!}
          </div>
        </div>
        <div class="col-xs-4">
          <div class="form-group @if (!isset($adtype) || !$adtype->can_add_pic) hide @endif" data-check-type = 'photo'>
            {!! Form::label("can_update_pic", trans("backend.adtype.update_photos"), ['class' => 'label-text']) !!} {!! Form::select("can_update_pic", array(trans('backend.adtype.no'), trans('backend.adtype.yes')), null, ['class' => 'form-control']) !!}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-4">
          <div class="form-group">
            {!! Form::label("can_add_video", trans("Ajouter des videos"), ['class' => 'label-text']) !!} {!! Form::select("can_add_video", array(trans('backend.adtype.no'), trans('backend.adtype.yes')), null, ['class' => 'form-control']) !!}
          </div>
        </div>
        <div class="col-xs-4">
          <div class="form-group @if (!isset($adtype) || !$adtype->can_add_video) hide @endif" data-check-type = 'video'>
            {!! Form::label("nbr_video", trans("backend.adtype.videos_peer_ad"), ['class' => 'label-text']) !!} {!! Form::text("nbr_video", null, ['class' => 'form-control', 'placeholder' => 'Ex : 3']) !!}
          </div>
        </div>
        <div class="col-xs-4">
          <div class="form-group @if (!isset($adtype) || !$adtype->can_add_video) hide @endif" data-check-type = 'video'>
            {!! Form::label("can_update_video", trans("backend.adtype.update_videos"), ['class' => 'label-text']) !!} {!! Form::select("can_update_video", array(trans('backend.adtype.no'), trans('backend.adtype.yes')), null, ['class' => 'form-control']) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
  @include ('backend::errors.list')
</div>
<div class="box-footer">
  {!! Form::submit($submitButton, ["class" => "btn btn-primary pull-right"]) !!}
</div>
