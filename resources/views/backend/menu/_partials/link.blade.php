<div id="menu-link" class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">
        {!! trans('backend.menu.link.links') !!}
        </h3>
      </div><!-- /.box-header -->
      {!! Form::open(['route' => ['zxadmin.menu.store'], 'class' => 'form-horizontal']) !!}
        <div class="box-body">
          <div class="form-group">
            <label for="link" class="col-sm-2 control-label">{!! trans('backend.menu.link.url') !!}</label>
            <div class="col-sm-10">
              {!! Form::text("link", "http://", ['class' => 'form-control']) !!}
            </div>
          </div>
          <div class="form-group">
            <label for="link-name" class="col-sm-2 control-label">{!! trans('backend.menu.link.label') !!}</label>
            <div class="col-sm-10">
              {!! Form::text("name", null, ['class' => 'form-control', 'placeholder' => trans('backend.menu.link.label'), 'id' => 'link-name']) !!}
            </div>
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <input id="link-type" type="hidden" name="type" value="link">
          <input id="link-title" type="hidden" name="title">
          <input id="link-group" type="hidden" name="group_name" value="{{ $groupName }}">
          <button type="submit" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> {!! trans('backend.menu.link.add_to_menu') !!}</button>
        </div>
        <!-- /.box-footer -->
      {!! Form::close() !!}
    </div><!-- /.box -->
  </div>
</div>
