<fieldset>
  <legend>{{ trans("backend.setting.ads")}}</legend>
  <ul class="todo-list">
    <li>
        {!! Form::label("auto_approve", trans("backend.setting.type_of_approval"), ['class' => 'label-text']) !!}
        <span class="pull-right">
        <select name="auto_approve" class="form-control">
            <option value="0" @if ($setting->auto_approve == 0) selected @endif>{!! trans('backend.setting.auto_approval') !!}</option>
            <option value="1" @if ($setting->auto_approve == 1) selected @endif>{!! trans('backend.setting.manual_approval') !!}</option>
        </select>
      </span>
    </li>
  </ul>
</fieldset>
