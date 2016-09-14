<?php

namespace ZEDx\Http\Controllers\Backend;

use Request;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\DashboardWidgetCreateRequest;
use ZEDx\Http\Requests\DashboardWidgetUpdateRequest;
use ZEDx\Models\Dashboardwidget;

class DashBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $dashboardWidgets = Dashboardwidget::orderBy('position', 'asc')->get();

        return view_backend('dashboard', compact('dashboardWidgets'));
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  Dashboardwidget              $dashboardwidget
   * @param  DashboardWidgetUpdateRequest $request
   *
   * @return Reponse
   */
  public function update(Dashboardwidget $dashboardwidget, DashboardWidgetUpdateRequest $request)
  {
      if ($request->has('config')) {
          $dashboardwidget->config = $request->get('config');
          $dashboardwidget->save();
      }

      if ($request->ajax()) {
          $dashboardwidget->update(array_filter($request->only('title', 'size')));
      }

      if (!$request->ajax()) {
          return back();
      }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  DashboardWidgetCreateRequest $request
   *
   * @return Reponse
   */
  public function store(DashboardWidgetCreateRequest $request)
  {
      Dashboardwidget::create($request->all());

      return redirect()->route('zxadmin.dashboard.index');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Dashboardwidget  $dashboardwidget
   *
   * @return Response
   */
  public function destroy(Dashboardwidget $dashboardwidget)
  {
      $dashboardwidget->delete();

      return redirect()->route('zxadmin.dashboard.index');
  }
}
