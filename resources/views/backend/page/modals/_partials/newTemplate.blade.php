<div class="row">
  <div class="col-md-4">
    <h5>{!! trans('backend.page.new_template') !!}</h5>
  </div>
  <div class="col-md-8">
    <select class="select2 form-control" id="templatesList">
      <option selected disabled>{!! trans('backend.page.choose_template') !!}</option>
      @foreach (\ZEDx\Models\Template::all() as $template)
      @if ($page->template->id != $template->id)
      <option value="{{ $template->file }}" data-id="{{ $template->id }}">{{ $template->title }}</option>
      @endif
      @endforeach
    </select>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-md-12">
    <div id="templates">
      @foreach (\ZEDx\Models\Template::all() as $template)
      @if ($page->template->id != $template->id)
      <div id="template_{{ $template->file }}" class="template-new-schema hide">
      {{--*/ $templateSkeleton = new TemplateSkeleton /*--}}
      {!! $templateSkeleton::renderForConnecting($template->skeleton, 'new') !!}
      </div>
      @endif
      @endforeach
    </div>
  </div>
</div>
