<?php

namespace ZEDx\Http\Controllers\Frontend\Ad;

use Auth;
use Illuminate\Http\Request;
use ZEDx\Events\Ad\AdWillBeDisplayed;
use ZEDx\Events\Ad\AdWillBePreviewed;
use ZEDx\Events\Search\SearchEngineRequested;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Models\Ad;
use ZEDx\Services\Frontend\Ad\AdService;
use ZEDx\Services\Frontend\PageService;

class AdController extends Controller
{
    /**
     * The page service instance.
     */
    protected $pageService;

    /**
     * Create a new controller instance.
     *
     * @param PageService $service
     *
     * @return void
     */
    public function __construct(PageService $service)
    {
        $this->pageService = $service;
    }

    /**
     * Display the specified resource.
     *
     * @param Ad $ad
     *
     * @return Response
     */
    public function show(Ad $ad)
    {
        event(new AdWillBeDisplayed($ad));

        $page = (object) $this->pageService->show('ad.show', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }

    /**
     * Ad preview.
     *
     * @param Ad $ad
     *
     * @return Response
     */
    public function preview(Ad $ad)
    {
        $actor = Auth::check() ? Auth::user() : Auth::guard('admin')->user();

        event(new AdWillBePreviewed($ad, $actor));

        $page = (object) $this->pageService->show('ad.show', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }

    public function contact(Ad $ad, Request $request)
    {
        return (new AdService())->contact($ad, $request);
    }

    public function phone(Ad $ad)
    {
        return (new AdService())->phone($ad);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function search($params = '')
    {
        event(new SearchEngineRequested($params));

        $page = (object) $this->pageService->show('ad.search', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }
}
