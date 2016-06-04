<fieldset>
  <legend>{!! trans("backend.setting.notification_user") !!}</legend>
  <ul class="todo-list">

    <li>
      {!! Form::label("new_user_welcome_message", trans("backend.setting.new_user_welcome_message"), ['class' => 'label-text']) !!}
      <span class="pull-right">
      @include('backend::setting._partials.notificationsUserOptions', ['name' => "new_user_welcome_message"])
      </span>
    </li>
    <li>
      {!! Form::label("tell_client_ad_accepted", trans("backend.setting.tell_client_ad_accepted"), ['class' => 'label-text']) !!}
      <span class="pull-right">
      @include('backend::setting._partials.notificationsUserOptions', ['name' => "tell_client_ad_accepted"])
      </span>
    </li>
    <li>
      {!! Form::label("tell_client_ad_refused", trans("backend.setting.tell_client_ad_refused"), ['class' => 'label-text']) !!}
      <span class="pull-right">
      @include('backend::setting._partials.notificationsUserOptions', ['name' => "tell_client_ad_refused"])
      </span>
    </li>
    <li>
      {!! Form::label("tell_client_ad_deleted", trans("backend.setting.tell_client_ad_deleted"), ['class' => 'label-text']) !!}
      <span class="pull-right">
      @include('backend::setting._partials.notificationsUserOptions', ['name' => "tell_client_ad_deleted"])
      </span>
    </li>
    <li>
      {!! Form::label("tell_client_ad_expired", trans("backend.setting.tell_client_ad_expired"), ['class' => 'label-text']) !!}
      <span class="pull-right">
      @include('backend::setting._partials.notificationsUserOptions', ['name' => "tell_client_ad_expired"])
      </span>
    </li>
    <li>
      {!! Form::label("tell_client_ad_type_changed", trans("backend.setting.tell_client_ad_type_changed"), ['class' => 'label-text']) !!}
      <span class="pull-right">
      @include('backend::setting._partials.notificationsUserOptions', ['name' => "tell_client_ad_type_changed"])
      </span>
    </li>
    <li>
      {!! Form::label("tell_client_new_subscr", trans("backend.setting.tell_client_new_subscr"), ['class' => 'label-text']) !!}
      <span class="pull-right">
      @include('backend::setting._partials.notificationsUserOptions', ['name' => "tell_client_new_subscr"])
      </span>
    </li>
  </ul>
</fieldset>
