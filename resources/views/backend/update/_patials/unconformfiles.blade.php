<h2>{{ trans('backend.update.unconform_list') }}</h2>
<ul>
@foreach ($changedFiles as $file)
  <li>{{ $file }}</li>
@endforeach
</ul>
