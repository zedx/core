<div id="menu-route" class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">
        {!! trans('backend.menu.route.routes') !!}
        </h3>
      </div><!-- /.box-header -->
      {!! Form::open(['route' => ['zxadmin.menu.store'], 'class' => 'form-horizontal']) !!}
        <div class="box-body">
          <div class="form-group">
            <label for="route" class="col-sm-2 control-label">{!! trans('backend.menu.route.route') !!}</label>
            <div class="col-sm-10">
              {!! Form::text("link", "", ['class' => 'form-control']) !!}
            </div>
          </div>
          <div class="form-group">
            <label for="route-name" class="col-sm-2 control-label">{!! trans('backend.menu.route.label') !!}</label>
            <div class="col-sm-10">
              {!! Form::text("name", null, ['class' => 'form-control', 'placeholder' => trans('backend.menu.route.label'), 'id' => 'route-name']) !!}
            </div>
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <input id="route-type" type="hidden" name="type" value="route">
          <input id="route-title" type="hidden" name="title">
          <input id="route-group" type="hidden" name="group_name" value="{{ $groupName }}">
          <button type="submit" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> {!! trans('backend.menu.route.add_to_menu') !!}</button>
        </div>
        <!-- /.box-footer -->
      {!! Form::close() !!}
    </div><!-- /.box -->
  </div>
</div>
