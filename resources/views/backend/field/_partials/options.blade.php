<label>&nbsp;</label>
<fieldset>
  <legend>{{ trans("backend.field.options_list")}}</legend>
  <script type="x-tmpl-mustache" id="optionsTemplate">
  @{{#.}}
    <div class="form-group" id="option_f@{{id}}">
      <div class="input-group">
        <span class="input-group-addon zx-move-option"><i class="fa fa-arrows"></i></span>
        <input type="text" class="form-control" value="@{{name}}" name="options[f@{{id}}]" />
        <span class="input-group-btn"><button type="button" class="btn btn-danger removeFieldOption" data-option-id="@{{id}}"><i class="fa fa-remove"></i></button></span>
      </div>
    </div>
  @{{/.}}
  </script>
  <div class="zedx-list-options"></div>
  <div class="form-group">
   <button type="button" id="add_option" class="btn btn-success btn-block"><i class="fa fa-plus"></i> {!! trans('backend.field.add_option') !!} </button>
  </div>
</fieldset>
