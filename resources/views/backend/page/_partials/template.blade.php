<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">{!! trans('backend.page.template_preview') !!}</h3>
    <div class="box-tools pull-right">
      <button class="btn btn-box-tool" data-toggle="modal" data-target="#confirmSwitchTemplateAction"><i class="fa fa-edit"></i></button>
    </div>
  </div><!-- /.box-header -->
  <div class="box-body">
    <div class="row">
      <div class="col-md-12">
      {!! TemplateSkeleton::renderForPage($page, $templateblock->identifier) !!}
      </div><!-- /.col -->

    </div><!-- /.row -->
  </div><!-- ./box-body -->
  <div class="box-footer">
    @include('backend::page._partials.themePartials')
  </div>
</div><!-- /.box -->
@include('backend::page.modals.switchTemplate')
