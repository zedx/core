<!-- Modal -->
<div class="modal fade" id="dialog-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i id="countryFlag" class=""></i> {!! trans('backend.map.personalization') !!}</h4>
      </div>
      <div class="modal-body">
        <!-- Color Picker -->
        <div class="form-group">
          <div class="label-text">{!! trans("backend.map.areas_color") !!}</div>
          <input type="text" class="form-control minicolors" id="ZxMap-fill" />
        </div><!-- /.form group -->


        <div class="form-group">
          <div class="label-text">{!! trans("backend.map.areas_color_on_mouse_over") !!} </div>
          <input type="text" class="form-control minicolors" id="ZxMap-animate-fill" />
        </div>
        <div class="form-group">
          <div class="label-text">{!! trans("backend.map.color_lines_separating_areas") !!} </div>
          <input type="text" class="form-control minicolors" id="ZxMap-stroke" />
        </div>
        <div class="form-group">
          <div class="label-text">{!! trans("backend.map.size_of_lines_separating_areas") !!} </div>
          <div class="input-group">
            <input type="text" class="form-control" id="ZxMap-stroke-width" />
            <div class="input-group-addon">
              px
            </div>
          </div><!-- /.input group -->
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <div class="label-text">{!! trans("backend.map.width") !!} </div>
              <input type="text" class="form-control" id="ZxMap-width" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="label-text">{!! trans("backend.map.height") !!} </div>
            <input type="text" class="form-control" id="ZxMap-height" />
          </div>
        </div>
        <div id="ZxMap-response" class="hide">
          <div class="alert alert-danger" role="alert">
            <span class="fa fa-exclamation-circle" aria-hidden="true"></span>
            <span class="response-text"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{!! trans('backend.map.cancel') !!}</button>
        <button id="ZxMap-submit" type="button" class="btn btn-primary" data-url="">{!! trans('backend.map.save') !!}</button>
      </div>
    </div>
  </div>
</div>
