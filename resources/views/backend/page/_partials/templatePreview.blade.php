<h4><center>{!! trans('backend.page.template_preview') !!}</center></h4>
<div class="row">
  <div class="col-md-12">
    <div id="templates">
      @foreach (\ZEDx\Models\Template::all() as $template)
      <div id="template_preview_{{ $template->id }}" class="template-preview-schema hide">
      {{--*/ $templateSkeleton = new TemplateSkeleton /*--}}
      {!! $templateSkeleton::renderForConnecting($template->skeleton, 'new') !!}
      </div>
      @endforeach
    </div>
  </div>
</div>
