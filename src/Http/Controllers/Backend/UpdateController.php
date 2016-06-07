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
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
      return view_backend('update.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
      return view_backend('page.create');
  }

  /**
   * Show specific resource.
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
