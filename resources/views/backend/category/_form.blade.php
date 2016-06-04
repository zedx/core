<div class="box-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        {!! Form::label("name", trans("backend.category.category_name"), ['class' => 'label-text']) !!}
        {!! Form::text("name", null, ['class' => 'form-control', 'placeholder' => 'Ex : Basic']) !!}
      </div>
      <div class="form-group">
        {!! Form::label("is_visible", trans("backend.category.visibility"), ['class' => 'label-text']) !!}
        {!! Form::select("is_visible", array("1" => trans('backend.category.visible_category'), "0" => trans('backend.category.hidden_category')), null, ['class' => 'form-control']) !!}
      </div>

      <div class="row">
        <div class="col-md-8">
          <div class="form-group">
            {!! Form::label("is_private", trans('backend.category.category_type'), ['class' => 'label-text']) !!}
            {!! Form::select("is_private", array(trans('backend.category.public'), trans('backend.category.protected')), null, ['id' => 'is_private', 'class' => 'form-control']) !!}
          </div>
        </div>
        <div id ='newcodes' class="col-md-4 hide">
            {!! Form::label("nbr_days", "&nbsp;", ['class' => 'label-text']) !!}
          {!! Form::button("<i class='fa fa-plus'></i> <span class='hidden-md'>" . trans('backend.category.add_a_code') . "</span>", ['id' => 'add_code', 'class' => 'btn btn-success form-control']) !!}
        </div>
      </div>
      <hr />
      <div class="row">
      <div class="col-md-12">
        <table class="table table-striped">
        <tbody id="codes" class ="hide" data-codes = "{{ isset($codes) ? json_encode($codes) : '[]' }}" data-code-validate-msg = '{"validate": "{!! trans('backend.category.code.validated') !!}", "expired": "{!! trans('backend.category.code.expired') !!}", "reached": "{!! trans('backend.category.code.reached') !!}"}'>
          <tr>
            <th>{!! trans("backend.category.code.code") !!}</th>
            <th>{!! trans("backend.category.code.validity") !!} <span  data-toggle="tooltip" data-original-title="{!! trans("backend.category.code.validity_question") !!}"><i class="fa fa-question-circle"></i></span></th>
            <th>{!! trans("backend.category.code.use") !!} <span data-toggle="tooltip" data-original-title="{!! trans("backend.category.code.max_use_help") !!}"><i class="fa fa-question-circle"></i></span></th>
            <th>{!! trans("backend.category.code.state") !!}</th>
            <th style="width: 40px"></th>
          </tr>
          <script type="x-tmpl-mustache" id="codesTemplate">
          @{{#.}}
            <tr>
              <td><input type="text" class="form-control" name="codes[@{{id}}][code]" placeholder="Votre code" value="@{{code}}"></td>
              <td><input type="text" class="form-control datepicker code_end_date" name="codes[@{{id}}][end_date]" data-date-format="dd/mm/yyyy" value="@{{end_date}}"></td>
              <td><input type="text" class="form-control code_max" name="codes[@{{id}}][max]" placeholder="inf" value="@{{max}}"/></td>
              <td class="code_validate_msg"><small class="label bg-green">{!! trans("backend.category.code.validated") !!}</small></td>
              <td><a href="javascript:void()" class="btn btn-xs btn-danger remove-code"><i class="fa fa-remove"></i> <span class="hidden-md hidden-xs">{!! trans("backend.category.code.delete") !!}</span></span></td>
            </tr>
          @{{/.}}
          </script>
          </tbody>
        </table>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <select id="zedx-fields" name="fields[]" data-selectr-opts='{ "title": "{!! trans("backend.category.field_list") !!}", "placeholder": "{!! trans("backend.category.filter") !!}", "resetText": "{!! trans("backend.category.unselect_all") !!}", "maxSelection": "Infinity" }' multiple>
        @if (isset($category))
        @foreach ($category->fields as $field)
        <option value="{{ $field->id }}" selected ><b>{{ $field->name }}</b> - {{ $field->title }}</option>
        @endforeach
        @endif
        @foreach ($fields as $field)
        <option value="{{ $field->id }}" ><b>{{ $field->name }}</b> - {{ $field->title }}</option>
        @endforeach
      </select>
    </div>
  </div>
  @include ('backend::errors.list')
</div>
<div class="box-footer">
  {!! Form::submit($submitButton, ["class" => "btn btn-primary pull-right"]) !!}
</div>
