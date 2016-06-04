<!-- Modal -->
<div class="modal fade" id="dialog-upload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i id="countryFlagUpload" class=""></i> {!! trans("backend.map.download_map") !!}</h4>
      </div>
      <div class="modal-body">
        <div class="form-group text-center">
          <button class="btn btn-primary"><i class="fa fa-download"></i> {!! trans("backend.map.download_map_from_store") !!}</button>
        </div>
        <!--
        <div class="form-group text-center">
          <p>{!! trans('backend.map.or') !!}</p>
        </div>
        <div class="form-group text-center">
          <form action="" id="uploadMapForm" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <span class="file-input btn btn-success btn-file">
              <i class="fa fa-camera"></i> {!! trans("backend.map.download_map_from_device") !!}
              <input id="uploadMapFile" type="file" name="map">
            </span>
          </form>
        </div>
        -->
        <div id="ZxMapUploadResponse" class="hide">
          <div class="alert alert-danger" role="alert">
            <span class="fa fa-exclamation-circle" aria-hidden="true"></span>
            <span class="response-text"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{!! trans("backend.map.finish") !!}</button>
      </div>
    </div>
  </div>
</div>
