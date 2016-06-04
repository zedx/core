<div class="box-body">
	<div class="row">
    @if (!isset($page) || (isset($page) && $page->type === 'page'))
		<div class="col-md-6">
			<div class="form-group">
				{!! Form::label("name", trans("backend.page.name"), ['class' => 'label-text']) !!}
				{!! Form::text("name", null, ['class' => 'form-control', 'placeholder' => 'Ex : Homepage']) !!}
			</div>
      <div class="form-group">
        {!! Form::label("shortcut", trans("backend.page.shortcut"), ['class' => 'label-text']) !!}
        {!! Form::text("shortcut", null, ['class' => 'form-control', 'placeholder' => 'Ex : homepage']) !!}
      </div>
      @if (Route::is('zxadmin.page.create'))
      <div class="form-group">
        {!! Form::label("template_id", trans("backend.page.template"), ['class' => 'label-text']) !!}
        <a href="{{ route('zxadmin.template.create') }}" class="btn btn-xs btn-info pull-right"><i class="fa fa-plus"></i> {{trans('backend.page.create_new_template') }}</a>
        <select class="select2 form-control" id="template_id" name="template_id">
        @foreach (ZEDx\Models\Template::all() as $template)
          <option value="{{ $template->id }}" {{ isset($page) && $template->id == $page->template_id ? 'selected': '' }}>{{ ucfirst($template->title) }}</option>
        @endforeach
        </select>
      </div>
      @include ('backend::page._partials.templatePreview')
      @endif
		</div>
    @endif
		<div class="col-md-6">
      <div class="form-group">
        {!! Form::label("description", trans("backend.page.description"), ['class' => 'label-text']) !!}
        {!! Form::textarea("description", null, ['class' => 'form-control','rows' => 4]) !!}
      </div>
      <div class="form-group">
        <label for="tags" class="label-text">{!! trans('backend.page.keywords') !!}</label>
        <input class="form-control" data-role="tagsinput" name="tags" type="text" id="tags" value="{{ isset($page) ? implode(',', $page->tags()->lists('name')->toArray()) : '' }}">
        <p><small>{!! trans('backend.page.keywords_help') !!}</small></p>
      </div>
		</div>

	</div>
  @include ('backend::errors.list')
</div>
<div class="box-footer">
	{!! Form::submit($submitButton, ["class" => "btn btn-primary pull-right"]) !!}
</div>
