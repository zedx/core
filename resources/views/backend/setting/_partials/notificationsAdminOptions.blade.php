<select name="{{ $name }}" class="form-control">
  <option value="0" @if ($setting->$name == 0) selected @endif>{!! trans('backend.setting.notification_none') !!}</option>
  <option value="1" @if ($setting->$name == 1) selected @endif>{!! trans('backend.setting.notification_by_email') !!}</option>
  <option value="2" @if ($setting->$name == 2) selected @endif>{!! trans('backend.setting.notification_on_website') !!}</option>
  <option value="3" @if ($setting->$name == 3) selected @endif>{!! trans('backend.setting.notification_email_website') !!}</option>
</select>
