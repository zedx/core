<div id="menu-page" class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">
        {!! trans('backend.menu.page.pages') !!}
        </h3>
      </div><!-- /.box-header -->
      {!! Form::open(['route' => ['zxadmin.menu.store'], 'class' => 'form-horizontal']) !!}
        <div class="box-body">
          <select id="page-link" class="select2 form-control" name="link">
            @foreach (\ZEDx\Models\Page::whereType('page')->get() as $page)
              <option value="{{ $page->id }}">{{ $page->name }}</option>
            @endforeach
          </select>
        </div><!-- /.box-body -->
        <div class="box-footer">
          <input id="page-type" type="hidden" name="type" value="page">
          <input id="page-name" type="hidden" name="name">
          <input id="page-title" type="hidden" name="title">
          <input id="page-group" type="hidden" name="group_name" value="{{ $groupName }}">
          <button type="submit" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> {!! trans('backend.menu.page.add_to_menu') !!}</button>
        </div>
      {!! Form::close() !!}
    </div><!-- /.box -->
  </div>
</div>
