<!-- Modal Dialog -->
<script type="x-tmpl-mustache" id="errorMessageTemplate">
<hr>
<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  @{{message}}
</div>
</script>
<div class="modal modal-wide fade confirmationDialog" id="confirmSwitchTemplateAction" role="dialog" aria-labelledby="confirmSwitchTemplateLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4>{!! trans('backend.page.change_template') !!}</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col col-md-6">
            @include('backend::page.modals._partials.currentTemplate')
          </div>
          <div class="col col-md-6">
            @include('backend::page.modals._partials.newTemplate')
          </div>
        </div>
        <div class="row">
          <div class="col col-md-12">
            <div id="error-response"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="{{ route('zxadmin.template.create') }}" class="btn btn-info pull-left"><i class="fa fa-plus"></i> {{trans('backend.page.create_new_template') }}</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('backend.page.cancel_template_change') }}</button>
        <button type="button" class="btn btn-primary" data-missing-blocks = "{!! trans('backend.page.red_blocks_must_be_connected') !!}" data-missing-template = "{!! trans('backend.page.choose_template') !!}" data-url="{{ route('zxadmin.page.switchTemplate', [$page->id]) }}" id="confirm">{{trans('backend.page.apply') }}</button>
      </div>
    </div>
  </div>
</div>
