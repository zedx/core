<data-ad-moderate data-ad-moderate-text='{!! trans("Annonce refusée") !!}' data-ad-moderate-route = ''>

<!-- Modal -->
<div class="modal fade confirmationDialog" id="dialogBanAd" tabindex="-1" role="dialog" aria-labelledby="dialogBanAdLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="banAdForm">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
        <h4>{!! trans("backend.ad.reason_for_ban") !!}</h4>
          <table class="table table-hover">
            <tbody id="adBanReasonList">
            @foreach (\ZEDx\Models\Reason::all() as $reason)
            <tr>
              <td class="vcenter col-md-1"><input type="checkbox" name="reasons[]" id="reaison_{{ $reason->id }}" value="{{ $reason->id }}"></td>
              <td class="vcenter" for="reaison_{{ $reason->id }}">{{ $reason->title }}</td>
            </tr>
            @endforeach
            </tbody>
          </table>
          <h4 class="pull-left">{!! trans('backend.ad.another_reason_for_ban') !!}</h4>
          <button type="button" id="addNewAdBanReason" class="btn btn-success pull-right"><i class="fa fa-plus"></i> <span class="hidden-xs">{!! trans('backend.ad.add') !!}</span></button>
          <script type="x-tmpl-mustache" id="adBanNewReasonTemplate">
            <tr>
              <td class="vcenter col-md-1"><input type="checkbox" name="newReasons[][title]"></td>
              <td class="vcenter"><input type="text" class="form-control"></td>
            </tr>
          </script>
          <table class="table table-hover">
            <tbody id="adBanNewReasonList">
            </tbody>
          </table>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('backend.ad.cancel_ban') }}</button>
          <button type="submit" class="btn btn-danger" data-dismiss="modal" id="confirm">{{trans('backend.ad.banish') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
