<select name="{{ $name }}" class="form-control">
  <option value="0" @if ($setting->$name == 0) selected @endif>{!! trans('backend.setting.notification_none') !!}</option>
  <option value="1" @if ($setting->$name == 1) selected @endif>{!! trans('backend.setting.notification_by_email') !!}</option>
</select>
