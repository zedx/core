<?php

namespace ZEDx\Http\Controllers\Frontend\User;

use Auth;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\CreateAdUserRequest;
use ZEDx\Http\Requests\UpdateAdUserRequest;
use ZEDx\Models\Ad;
use ZEDx\Models\Adstatus;
use ZEDx\Models\Adtype;
use ZEDx\Services\Frontend\PageService;
use ZEDx\Services\Frontend\User\AdService;

class AdController extends Controller
{
    /**
     * The page service instance.
     *
     * @var \ZEDx\Services\Frontend\PageService
     */
    protected $pageService;

    /**
     * Auth User.
     *
     * @var \ZEDx\Models\User
     */
    protected $user;

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
        $this->user = Auth::user();

        if ($this->user && !$this->user->is_validate) {
            redirect()->route('user.edit')->send();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page = (object) $this->pageService->show('user.ad.index', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function filterByStatus(Adstatus $adstatus)
    {
        $page = (object) $this->pageService->show('user.ad.index', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Adtype $adtype)
    {
        if ($this->numberAdtype($adtype) <= 0 && $adtype->price > 0) {
            return redirect()->route('user.adtype.index');
        }

        if ($this->user->subscription_expired_at) {
            if ($this->user->subscription_expired_at->diffInDays(null, false) >= 0) {
                return redirect()->route('user.subscription.index');
            }
        }

        $page = (object) $this->pageService->show('user.ad.create', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }

    /**
     * Adtype number.
     *
     * @param Adtype $adtype
     *
     * @return int
     */
    private function numberAdtype(Adtype $adtype)
    {
        return $this->user
            ->adtypes
            ->find($adtype->id)
            ->pivot
            ->number;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Adtype $adtype, CreateAdUserRequest $request)
    {
        $item = (object) (new AdService())->store($adtype, $request);

        if ($item->adId === null) {
            return redirect()->route('user.adtype.index');
        }

        return redirect()->route('user.ad.edit', $item->adId);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Ad $ad)
    {
        if (!$ad->adtype->can_edit) {
            return redirect()->route('user.ad.index');
        }

        $page = (object) $this->pageService->show('user.ad.edit', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Ad $ad, UpdateAdUserRequest $request)
    {
        $item = (object) (new AdService())->update($ad, $request);

        if ($item->adId === null) {
            return redirect()->route('user.ad.index');
        }

        return redirect()->route('user.ad.edit', $item->adId);
    }

    public function renew(Ad $ad)
    {
        (new AdService())->renew($ad);

        return redirect()->route('user.ad.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Ad $ad
     *
     * @return Response
     */
    public function destroy(Ad $ad)
    {
        (new AdService())->destroy($ad);
    }
}
