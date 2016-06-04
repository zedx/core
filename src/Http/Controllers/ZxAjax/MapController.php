<?php

namespace ZEDx\Http\Controllers\ZxAjax;

use Illuminate\Http\Request;
use Maps;
use ZEDx\Http\Controllers\Controller;

class MapController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  string  $code
     *
     * @return Response
     */
    public function show($code, Request $request)
    {
        if ($request->wantsJson()) {
            $map = Maps::find($code);

            if (! $map) {
                return;
            }

            return $map->json()->getContents();
        } else {
            abort(404);
        }
    }
}
