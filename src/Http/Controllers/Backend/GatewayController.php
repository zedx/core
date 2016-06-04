<?php

namespace ZEDx\Http\Controllers\Backend;

use Illuminate\Http\Request;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\GatewaySetCurrencyRequest;
use ZEDx\Models\Gateway;
use ZEDx\Models\Country;

class GatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $gateways = Gateway::paginate(10);
        $currencies = Country::distinct()->where('currency', '<>', '')
            ->groupBy('currency')
            ->lists('currency')
            ->toArray();

        return view_backend('gateway.index', compact('gateways', 'currencies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit(Gateway $gateway)
    {
        $attributes = json_decode($gateway->options, true);

        return view_backend('gateway.edit', compact('gateway', 'attributes'));
    }

    /**
     * Set currency.
     *
     * @param Request $request
     */
    public function setCurrency(GatewaySetCurrencyRequest $request)
    {
        $setting = setting();
        $setting->currency = $request->currency;
        $setting->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update(Gateway $gateway, Request $request)
    {
        $inputs = $request->get('options', []);
        $gateway->options = $this->hydrateOptions($gateway, $inputs);
        $gateway->save();

        return redirect()->route('zxadmin.gateway.edit', $gateway->id)->with('message', 'success');
    }

    /**
     * Make real boolean.
     *
     * @param  Gateway $gateway
     * @param  array  $inputs
     *
     * @return array
     */
    protected function hydrateOptions(Gateway $gateway, $inputs)
    {
        $options = [];
        $attributes = json_decode($gateway->options, true);
        foreach ($attributes as $attribute => $value) {
            if (is_bool($value)) {
                $options[$attribute] = $inputs[$attribute] == '1' ? true : false;
            } else {
                $options[$attribute] = $inputs[$attribute];
            }
        }

        return json_encode($options);
    }

    /**
     * Enable/Disable a gateway.
     *
     * @param  Gateway $gateway
     *
     * @return Response
     */
    public function switchStatus(Gateway $gateway)
    {
        $gateway->enabled = ! $gateway->enabled;
        $gateway->save();
    }
}
