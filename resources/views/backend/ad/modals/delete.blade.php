<!-- Modal Dialog -->
<div class="modal fade confirmationDialog" id="confirmDeleteAction" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="modal-message"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal" id="confirm"><i class="fa fa-remove"></i> {{trans('backend.ad.delete_permanently') }}</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('backend.ad.cancel_delete') }}</button>
        <button type="button" class="btn btn-warning" data-dismiss="modal" id="confirmToTrash"><i class="fa fa-trash"></i> {{trans('backend.ad.move_to_trash') }}</button>
      </div>
    </div>
  </div>
</div>
