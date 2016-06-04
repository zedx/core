<div class="row">
  <div class="col-md-12">
    <h5><center>{!! trans('backend.page.current_template') !!}</center></h5>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-md-12">
    <div class="template-current-schema">
    {{--*/ $templateSkeleton = new TemplateSkeleton /*--}}
    {!! $templateSkeleton::renderForConnecting($page->template->skeleton, 'current', $page) !!}
    </div>
  </div>
</div>
