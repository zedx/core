<div class="box-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        {!! Form::label("title", trans("backend.template.title"), ['class' => 'label-text']) !!} {!! Form::text("title", null, ['id' => 'template-title', 'class' => 'form-control', 'placeholder' => 'Ex : Basic']) !!}
      </div>
    </div>
  </div>
  <script type="x-tmpl-mustache" id="TemplateRowTemplate">
    <div class="row template-editing ui-sortable">
    <div class="template-tools clearfix">
      <a href="javascript:void(0)" title="{!! trans('backend.template.move_row') !!}" class="template-moveRow pull-left"><i class="fa fa-arrows"></i> </a>
      <a title="{!! trans('backend.template.add_col') !!}" class="template-addColumn pull-left"><i class="fa fa-plus"></i> </a>
    </div>
    @{{{column}}}
    <div class="template-tools clearfix"><a title="{!! trans('backend.template.delete_row') !!}" class="pull-right template-removeRow"><span class="fa fa-trash-o"></span></a> </div>
    </div>
  </script>

  <script type="x-tmpl-mustache" id="TemplateColumnTemplate">
    <div class="col-md-@{{grid}} column template-editing ui-sortable" data-template-grid="@{{grid}}">
      <div class="template-tools clearfix">
        <a href="javascript:void(0)" title="{!! trans('backend.template.move_col') !!}" class="template-moveCol pull-left"><i class="fa fa-arrows"></i> </a>
        <a href="javascript:void(0)" title="{!! trans('backend.template.decrease_col') !!}" class="template-colDecrease pull-left"><i class="fa fa-minus"></i> </a>
        <a href="javascript:void(0)" title="{!! trans('backend.template.increase_col') !!}" class="template-colIncrease pull-left"><i class="fa fa-plus"></i></a>
      </div>
      @{{{block}}}
      <div class="template-tools clearfix">
        <a href="javascript:void(0)" title="{!! trans('backend.template.add_row') !!}" class="pull-left template-addRow"><i class="fa fa-plus-square"></i></a>
        <a href="javascript:void(0)" title="{!! trans('backend.template.delete_col') !!}" class="pull-right template-removeCol"><i class="fa fa-trash-o"></i></a>
      </div>
    </div>
  </script>

  <script type="x-tmpl-mustache" id="TemplateNewBlockTemplate">
    <div class="template-editable-region">
      <h4><center><i class="fa fa-edit"></i> <span class="template-block-title">{!! trans('backend.template.new_block_name') !!}</span></center></h4>
    </div>
  </script>
  <div class="row">
    <div class="col-md-12">
      <h3>{!! trans('backend.template.template_personalization') !!} <div class="pull-right"><a href="javascript:void(0)" class="template-addNewRow btn btn-primary"><i class="fa fa-plus"></i> {!! trans('backend.template.add_row') !!}</a></div></h3>
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-12">
      <div id="template-canvas" @if (isset($template)) data-identifier="{{ $template->identifier }}" @endif>
      @if (isset($template))
      {!! TemplateSkeleton::renderForEditing($template->skeleton, 'edit') !!}
      @endif
      </div>
    </div><!-- /.col -->
  </div><!-- /.row -->
  <div class="row">
    <div class="col-md-12">
      <h3>{!! trans('backend.template.template_preview') !!}</h3>
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-12">
      <div id="template-render-preview"></div>
    </div><!-- /.col -->
  </div><!-- /.row -->
  @include ('backend::errors.list')
</div>
  <input type="hidden" id="templateSkeleton" name="skeleton">

<div class="box-footer">
  {!! Form::submit($submitButton, ["class" => "btn btn-success pull-right"]) !!}
</div>
