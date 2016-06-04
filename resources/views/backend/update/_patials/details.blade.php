<ul class="nav nav-stacked">
  <li><a href="#"><b>-- {{ trans('backend.update.new_version') }}</b> <span class="pull-right badge">{{ Updater::getLatest()->version }}</span></a></li>
  <li><a href="#"><b>[ + ]</b> {{ trans('backend.update.added_files') }} <span class="pull-right badge bg-green"> {{ count(Updater::getJsonUpdate()->files->A) }}</span></a></li>
  <li><a href="#"><b>[ * ]</b> {{ trans('backend.update.updated_files') }} <span class="pull-right badge bg-aqua">{{ count(Updater::getJsonUpdate()->files->U) }}</span></a></li>
  <li><a href="#"><b>[ - ]</b> {{ trans('backend.update.deleted_files') }} <span class="pull-right badge bg-red">{{ count(Updater::getJsonUpdate()->files->D) }}</span></a></li>
  <li><a href="#"><b>[ = ]</b> {{ trans('backend.update.conformity') }}
  @if (empty($changedFiles))
  <span class="fa fa-check-square-o text-green pull-right"></span>
  @else
  <span class="fa fa-times text-red pull-right"></span>
  @endif
  </a>
  </li>
  <li>
  @if (!empty($changedFiles))
    <div class="alert alert-danger"><i class="icon fa fa-ban"></i> {{ trans('backend.update.conformity_error') }}</div>
  @endif
  @if (empty($changedFiles) || $force)
  <button id="start-zedx-updater" data-force="{{ $force ? '1' : '0'}}" data-update-url="{{ route('zxadmin.update.show', ['zedx']) }}" class="btn btn-block btn-success"><i class="fa fa-refresh"></i> {{ trans('backend.update.start_update') }}</button>
  @endif
  </li>
</ul>
