<?php

namespace ZEDx\Http\Controllers\Frontend;

use ZEDx\Http\Controllers\Controller;
use ZEDx\Services\Frontend\PageService;

class PageController extends Controller
{
    /**
   * The page service instance.
   */
  protected $service;

  /**
   * Create a new controller instance.
   *
   * @param  PageService  $service
   *
   * @return void
   */
  public function __construct(PageService $service)
  {
      $this->service = $service;
  }

  /**
   * Show the homepage.
   *
   * @return Response
   */
  public function index()
  {
      return $this->show();
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   *
   * @return Response
   */
  public function show($shortcut = '/')
  {
      $page = (object) $this->service->show($shortcut);

      return view('__templates::'.$page->templateFile, $page->data);
  }
}
