<!-- Modal Dialog -->
<div class="modal fade confirmationDialog" id="confirmEditAction" role="dialog" aria-labelledby="confirmEditLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"></h4>
      </div>

      <script type="x-tmpl-mustache" id="menuLinkEditTemplate">
      <form action="{{ route('zxadmin.menu.update', '') }}/@{{id}}" class="form-horizontal" method="POST" >
        <input name="_method" type="hidden" value="PATCH">
        <input name="_token" type="hidden" value="{{ csrf_token() }}">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-2 control-label">{!! trans('backend.menu.link.url') !!}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="link" value="@{{link}}">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">{!! trans('backend.menu.link.label') !!}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="name" value="@{{name}}">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">{!! trans('backend.menu.link.title') !!}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="title" value="@{{title}}">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">{!! trans('backend.menu.link.icon') !!}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="icon" value="@{{icon}}">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{!! trans('backend.menu.cancel_delete') !!}</button>
          <button type="submit" class="btn btn-primary">{{trans('backend.menu.edit_menu') }}</button>
        </div>
      </form>
      </script>
      <script type="x-tmpl-mustache" id="menuPageEditTemplate">
      <form action="{{ route('zxadmin.menu.update', '') }}/@{{id}}" class="form-horizontal" method="POST" >
        <input name="_method" type="hidden" value="PATCH">
        <input name="_token" type="hidden" value="{{ csrf_token() }}">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-2 control-label">{!! trans('backend.menu.page.label') !!}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="name" value="@{{name}}">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">{!! trans('backend.menu.page.title') !!}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="title" value="@{{title}}">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">{!! trans('backend.menu.page.icon') !!}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="icon" value="@{{icon}}">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{!! trans('backend.menu.cancel_delete') !!}</button>
          <button type="submit" class="btn btn-primary">{{trans('backend.menu.edit_menu') }}</button>
        </div>
      </form>
      </script>
      <div id="menuEditContent"></div>
    </div>
  </div>
</div>
