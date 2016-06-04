<fieldset>
  <legend>{{ trans("backend.setting.notification_admin")}}</legend>
  <ul class="todo-list">
    <li>
      {!! Form::label("tell_me_payment_received", trans("backend.setting.tell_me_payment_received"), ['class' => 'label-text']) !!}
      <span class="pull-right">
     @include('backend::setting._partials.notificationsAdminOptions', ['name' => "tell_me_payment_received"])
      </span>
    </li>
    <li>
      {!! Form::label("tell_me_new_user", trans("backend.setting.tell_me_new_user"), ['class' => 'label-text']) !!}
      <span class="pull-right">
     @include('backend::setting._partials.notificationsAdminOptions', ['name' => "tell_me_new_user"])
      </span>
    </li>
    <li>
      {!! Form::label("tell_me_new_ads", trans("backend.setting.tell_me_new_ads"), ['class' => 'label-text']) !!}
      <span class="pull-right">
      @include('backend::setting._partials.notificationsAdminOptions', ['name' => "tell_me_new_ads"])
      </span>
    </li>
    <li>
      {!! Form::label("tell_me_edit_ads", trans("backend.setting.tell_me_edit_ads"), ['class' => 'label-text']) !!}
      <span class="pull-right">
      @include('backend::setting._partials.notificationsAdminOptions', ['name' => "tell_me_edit_ads"])
      </span>
    </li>
    <li>
      {!! Form::label("tell_me_renew_ads", trans("backend.setting.tell_me_renew_ads"), ['class' => 'label-text']) !!}
      <span class="pull-right">
      @include('backend::setting._partials.notificationsAdminOptions', ['name' => "tell_me_renew_ads"])
      </span>
    </li>
    <li>
      {!! Form::label("tell_me_payment_ads", trans("backend.setting.tell_me_payment_ads"), ['class' => 'label-text']) !!}
      <span class="pull-right">
      @include('backend::setting._partials.notificationsAdminOptions', ['name' => "tell_me_payment_ads"])
      </span>
    </li>
    <li>
      {!! Form::label("tell_me_new_payment_subscr", trans("backend.setting.tell_me_new_payment_subscr"), ['class' => 'label-text']) !!}
      <span class="pull-right">
      @include('backend::setting._partials.notificationsAdminOptions', ['name' => "tell_me_new_payment_subscr"])
      </span>
    </li>
  </ul>
</fieldset>
