<?php

namespace ZEDx\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Session;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Updater;
use ZEDx\Http\Controllers\Controller;

class UpdateController extends Controller
{
    /**
     * Show update.
     *
     * @param Request $request
     * @param string  $type
     * @param string  $group
     * @param string  $name
     *
     * @return Response
     */
    public function show(Request $request, $type = 'zedx', $group = 'zedx', $name = 'zedx')
    {
        if ($request->has('install') && $request->get('_token') == Session::token()) {
            return $this->startUpdate($request, $type, $group, $name);
        }

        if (Updater::isLatest()) {
            return redirect()->route('zxadmin.dashboard.index');
        }

        $changedFiles = Updater::getChangedFiles();
        $hasForce = $request->has('force');
        $force = $hasForce && $request->get('force') == 'true';

        $data = compact('type', 'group', 'name', 'changedFiles', 'force');
        if ($type == 'zedx') {
            return view_backend('update.zedx', $data);
        }

        return view_backend('update.component', $data);
    }

    /**
     * Start update.
     *
     * @param Request $request
     * @param string  $type
     * @param string  $group
     * @param string  $name
     *
     * @return Response
     */
    protected function startUpdate(Request $request, $type = 'zedx', $group = 'zedx', $name = 'zedx')
    {
        $response = new StreamedResponse(function () use ($request) {
            $hasForce = $request->has('force');
            $force = $hasForce && $request->get('force') == 'true';

            Updater::update($force);
        });

        $response->headers->set('Content-Type', 'text/event-stream');

        return $response;
    }
}
