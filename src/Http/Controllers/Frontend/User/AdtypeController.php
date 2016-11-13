<?php

namespace ZEDx\Http\Controllers\Frontend\User;

use Auth;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\AdtypePurchaseRequest;
use ZEDx\Models\Adtype;
use ZEDx\Services\Frontend\PageService;
use ZEDx\Services\Frontend\User\AdtypeService;

class AdtypeController extends Controller
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
        if ($this->user->subscription_expired_at) {
            if ($this->user->subscription_expired_at->diffInDays(null, false) >= 0) {
                return redirect()->route('user.subscription.index');
            }
        }

        if ($this->user->hasOnlyOneFreeAdtype()) {
            return redirect()->route('user.ad.create', $this->user->adtypes->first());
        }

        $page = (object) $this->pageService->show('user.adtype.index', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }

    public function cart(Adtype $adtype)
    {
        if ($adtype->price <= 0) {
            return redirect()->route('user.adtype.index');
        }

        $page = (object) $this->pageService->show('user.adtype.cart', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }

    public function checkout(Adtype $adtype, AdtypePurchaseRequest $request)
    {
        (new AdtypeService())->checkout($adtype, $request);
    }
}
