<?php

namespace ZEDx\Http\Controllers\Backend;

use Maps;
use Request;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\CountryPersonalizeRequest;
use ZEDx\Http\Requests\CountrySwitchRequest;
use ZEDx\Http\Requests\CountryUpdateSymboleRequest;
use ZEDx\Http\Requests\CountryUploadMapRequest;
use ZEDx\Models\Country;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $countries = Country::search(Request::get('q'))->paginate(10);

        return view_backend('country.index', compact('countries'));
    }

    /**
     * Switch country status.
     *
     * @param CountrySwitchRequest $request
     * @param Country              $country
     *
     * @return Response
     */
    public function swap(CountrySwitchRequest $request, Country $country)
    {
        $is_activate = Maps::exists($country->code) ? $request->get('is_activate') : '0';
        $country->update(['is_activate' => $is_activate]);
    }

    /**
     * Update country currency symbole.
     *
     * @param CountryUpdateSymboleRequest $request
     * @param Country                     $country
     *
     * @return Response
     */
    public function updateSymbole(CountryUpdateSymboleRequest $request, Country $country)
    {
        $country->currency_symbole = $request->symbole;
        $country->save();
    }

    /**
     * Personalize a country map.
     *
     * @param CountryPersonalizeRequest $request
     * @param Country                   $country
     *
     * @return Response
     */
    public function personalize(CountryPersonalizeRequest $request, Country $country)
    {
        $attributes = $request->only([
            'fill', 'animate-fill',
            'stroke', 'stroke-width',
            'width', 'height',
        ]);

        $map = Maps::find($country->code);

        if (!$map) {
            return;
        }

        $map->setAttributes($attributes);
    }

    /**
     * Upload Map JSON.
     *
     * @param Request $request
     * @param Country $country
     *
     * @return Reponse
     */
    public function upload(CountryUploadMapRequest $request, Country $country)
    {
        $mapUploadedFile = $request->file('map');
        if (!$mapUploadedFile || !$mapUploadedFile->isValid()) {
            return response(trans('backend.map.invalid_map_file'), 400);
        }

        $mapFile = $mapUploadedFile->getPathname();

        if (!Maps::isValidMapFile($mapFile)) {
            return response(trans('backend.map.invalid_map_file'), 400);
        }

        $destinationPath = storage_path('app'.DIRECTORY_SEPARATOR.'maps');
        $mapUploadedFile->move(Maps::getPath(), strtoupper($country->code).'.json');

        return response('ok');
    }
}
