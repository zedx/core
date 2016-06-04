<table class="table table-hover">
    <tr>
        <th></th>
        <th><strong>Provider</strong></th>
        <th>Client ID</th>
        <th>Secret Key</th>
        <th>Status</th>
    </tr>
    @foreach (json_decode($setting->social_auths) as $providerName => $provider)
    <tr data-provider="{{ $providerName }}">
        <td><span class="btn azm-social azm-size-32 azm-circle azm-{{ $provider->icon }}"><i class="fa fa-{{ $provider->icon }}"></i></span></td>
        <td class="vcenter">{{ ucfirst($providerName) }}</td>
        <td class="vcenter"><input type="hidden" id="client_id_{{ $providerName }}" name="social_auths[{{ $providerName }}][client_id]" value="{{ $provider->client_id }}"><i class="fa fa-edit"></i> <span class="provider-client">{{ $provider->client_id }}</span></td>
        <td class="vcenter"><input type="hidden" id="secret_key_{{ $providerName }}" name="social_auths[{{ $providerName }}][secret_key]" value="{{ $provider->secret_key }}"><i class="fa fa-edit"></i> <span class="provider-secret">{{ $provider->secret_key }}</span></td>
        <td class="vcenter">
            <label>
                <input type="hidden" name="social_auths[{{ $providerName }}][icon]" value="{{ $provider->icon }}">
                <input type="hidden" name="social_auths[{{ $providerName }}][enabled]" value="0">
                <input type="checkbox" name="social_auths[{{ $providerName }}][enabled]" value="1" class="minimal" @if($provider->enabled) checked @endif>
            </label>
        </td>
    </tr>
    @endforeach

</table>
