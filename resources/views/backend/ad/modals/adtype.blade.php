<!-- Modal -->
<div class="modal fade" id="dialogAdtype" tabindex="-1" role="dialog" aria-labelledby="dialogAdtypeLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{!! trans('backend.ad.personalization') !!}</h4>
      </div>
      <script type="x-tmpl-mustache" id="adTypePersonnalizeTemplate">
        <form action="{{ route('zxadmin.ad.index') }}/@{{adId}}/adtype/@{{id}}" method="POST" >
        {!! csrf_field() !!}
      <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-xs-4">
                  <div class="form-group">
                    <label class="label-text" for="is_headline">{!! trans("backend.adtype.headline_ad") !!}</label>
                    <select name="is_headline" id="is_headline" class="form-control">
                    @{{^is_headline}}
                      <option value="0" selected>{!! trans("backend.adtype.no") !!}</option>
                      <option value="1">{!! trans("backend.adtype.yes") !!}</option>
                      @{{/is_headline}}
                      @{{#is_headline}}
                      <option value="0">{!! trans("backend.adtype.no") !!}</option>
                      <option value="1" selected>{!! trans("backend.adtype.yes") !!}</option>
                      @{{/is_headline}}
                    </select>
                  </div>
                </div>
                <div class="col-xs-4">
                  <div class="form-group">
                    <label class="label-text" for="can_renew">{!! trans("backend.adtype.renew_an_ad") !!}</label>
                    <select name="can_renew" id="can_renew" class="form-control">
                    @{{^can_renew}}
                      <option value="0" selected>{!! trans("backend.adtype.no") !!}</option>
                      <option value="1">{!! trans("backend.adtype.yes") !!}</option>
                      @{{/can_renew}}
                      @{{#can_renew}}
                      <option value="0">{!! trans("backend.adtype.no") !!}</option>
                      <option value="1" selected>{!! trans("backend.adtype.yes") !!}</option>
                      @{{/can_renew}}
                    </select>
                  </div>
                </div>
                <div class="col-xs-4">
                  <div class="form-group">
                    <label class="label-text" for="can_edit">{!! trans("backend.adtype.edit_an_ad") !!}</label>
                    <select name="can_edit" id="can_edit" class="form-control">
                    @{{^can_edit}}
                      <option value="0" selected>{!! trans("backend.adtype.no") !!}</option>
                      <option value="1">{!! trans("backend.adtype.yes") !!}</option>
                      @{{/can_edit}}
                      @{{#can_edit}}
                      <option value="0">{!! trans("backend.adtype.no") !!}</option>
                      <option value="1" selected>{!! trans("backend.adtype.yes") !!}</option>
                      @{{/can_edit}}
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-4">
                  <div class="form-group">
                    <label class="label-text" for="can_add_pic">{!! trans("backend.adtype.add_photos") !!}</label>
                    <select name="can_add_pic" id="can_add_pic" class="form-control">
                    @{{^can_add_pic}}
                      <option value="0" selected>{!! trans("backend.adtype.no") !!}</option>
                      <option value="1">{!! trans("backend.adtype.yes") !!}</option>
                      @{{/can_add_pic}}
                      @{{#can_add_pic}}
                      <option value="0">{!! trans("backend.adtype.no") !!}</option>
                      <option value="1" selected>{!! trans("backend.adtype.yes") !!}</option>
                      @{{/can_add_pic}}
                    </select>
                  </div>
                </div>
                <div class="col-xs-4">
                @{{^can_add_pic}}
                  <div class="form-group hide" data-check-type = 'photo'>
                @{{/can_add_pic}}
                @{{#can_add_pic}}
                  <div class="form-group" data-check-type = 'photo'>
                @{{/can_add_pic}}
                    <label class="label-text" for="nbr_pic">{!! trans("backend.adtype.photos_peer_ad") !!}</label>
                    <input type="text" value="@{{nbr_pic}}" name="nbr_pic" id="nbr_pic" class="form-control" placeholder = "Ex: 5" />
                  </div>
                </div>
                <div class="col-xs-4">
                  @{{^can_add_pic}}
                    <div class="form-group hide" data-check-type = 'photo'>
                  @{{/can_add_pic}}
                  @{{#can_add_pic}}
                    <div class="form-group" data-check-type = 'photo'>
                  @{{/can_add_pic}}
                    <label class="label-text" for="can_update_pic">{!! trans("backend.adtype.update_photos") !!}</label>
                    <select name="can_update_pic" id="can_update_pic" class="form-control">
                    @{{^can_update_pic}}
                      <option value="0" selected>{!! trans("backend.adtype.no") !!}</option>
                      <option value="1">{!! trans("backend.adtype.yes") !!}</option>
                      @{{/can_update_pic}}
                      @{{#can_update_pic}}
                      <option value="0">{!! trans("backend.adtype.no") !!}</option>
                      <option value="1" selected>{!! trans("backend.adtype.yes") !!}</option>
                      @{{/can_update_pic}}
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-4">
                  <div class="form-group">
                    <label class="label-text" for="can_add_video">{!! trans("backend.adtype.add_videos") !!}</label>
                    <select name="can_add_video" id="can_add_video" class="form-control">
                    @{{^can_add_video}}
                      <option value="0" selected>{!! trans("backend.adtype.no") !!}</option>
                      <option value="1">{!! trans("backend.adtype.yes") !!}</option>
                      @{{/can_add_video}}
                      @{{#can_add_video}}
                      <option value="0">{!! trans("backend.adtype.no") !!}</option>
                      <option value="1" selected>{!! trans("backend.adtype.yes") !!}</option>
                      @{{/can_add_video}}
                    </select>
                  </div>
                </div>
                <div class="col-xs-4">
                  @{{^can_add_video}}
                    <div class="form-group hide" data-check-type = 'video'>
                  @{{/can_add_video}}
                  @{{#can_add_video}}
                    <div class="form-group" data-check-type = 'video'>
                  @{{/can_add_video}}
                    <label class="label-text" for="nbr_video">{!! trans("backend.adtype.videos_peer_ad") !!}</label>
                    <input type="text" value="@{{nbr_video}}" name="nbr_video" id="nbr_video" class="form-control" placeholder = "Ex: 3" />
                  </div>
                </div>
                <div class="col-xs-4">
                  @{{^can_add_video}}
                    <div class="form-group hide" data-check-type = 'video'>
                  @{{/can_add_video}}
                  @{{#can_add_video}}
                    <div class="form-group" data-check-type = 'video'>
                  @{{/can_add_video}}
                    <label class="label-text" for="can_update_video">{!! trans("backend.adtype.update_videos") !!}</label>
                    <select name="can_update_video" id="can_update_video" class="form-control">
                    @{{^can_update_video}}
                      <option value="0" selected>{!! trans("backend.adtype.no") !!}</option>
                      <option value="1">{!! trans("backend.adtype.yes") !!}</option>
                      @{{/can_update_video}}
                      @{{#can_update_video}}
                      <option value="0">{!! trans("backend.adtype.no") !!}</option>
                      <option value="1" selected>{!! trans("backend.adtype.yes") !!}</option>
                      @{{/can_update_video}}
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <div class="form-group">
                    <label class="label-text" for="nbr_days">{!! trans("backend.adtype.display_time_of_an_ad") !!}</label>
                    <div class="input-group">
                      <input type="text" value="@{{nbr_days}}" name="nbr_days" id="nbr_days" class="form-control" placeholder = "Ex: 60" />
                      <div class="input-group-addon">
                        <i>{!! trans('backend.adtype.days') !!}</i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <input name="_method" type="hidden" value="PATCH">
          <input type="hidden" name="price" value="0">
          <input type="hidden" name="title" value="customized_@{{title}}">
          </div>
          @include ('backend::errors.list')
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">{!! trans('backend.adtype.save') !!}</button>
          </div>
          </form>
          </script>
          <div id="adTypePersonnalize"></div>
    </div>
  </div>
</div>
