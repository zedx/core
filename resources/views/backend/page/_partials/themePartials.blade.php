@foreach (\ZEDx\Models\Themepartial::all() as $themePartial)
<div class="col-md-6">
  <label class="themepartial-label checkbox-inline">
    <input type="checkbox" class="themepartial-input" data-url="{{ route('zxadmin.page.attachthemepartial', [$page->id, $themePartial->id]) }}" {{ (isset($selectedThemePartials) && in_array($themePartial->id, $selectedThemePartials)) ? 'checked': "" }}> {{ $themePartial->title }}
  </label>
</div>
@endforeach
