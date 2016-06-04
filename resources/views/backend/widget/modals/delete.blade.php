<!-- Modal Dialog -->
<div class="modal fade confirmationDialog" id="confirmWidgetAction" role="dialog" aria-labelledby="confirmWidgetActionLabel" aria-hidden="true">
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
        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('Annuler') }}</button>
        <button type="button" class="btn btn-danger" id="confirm">{{trans('Supprimer') }}</button>
      </div>
    </div>
  </div>
</div>
