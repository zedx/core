<?php

namespace ZEDx\Http\Controllers\Frontend\User;

use Auth;
use Request;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\SubscriptionPurchaseRequest;
use ZEDx\Models\Subscription;
use ZEDx\Services\Frontend\PageService;
use ZEDx\Services\Frontend\User\SubscriptionService;

class SubscriptionController extends Controller
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
     * @param  PageService  $service
     *
     * @return void
     */
    public function __construct(PageService $service)
    {
        $this->pageService = $service;
        $this->user = Auth::user();

        if ($this->user && ! $this->user->is_validate) {
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
        $page = (object) $this->pageService->show('user.subscription.index', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }

    /**
     * Swap subscription.
     *
     * @return Response
     */
    public function swapForFree(Subscription $subscription)
    {
        $swapped = (new SubscriptionService())->swapForFree($subscription);

        if (! Request::ajax()) {
            return redirect()->route('user.subscription.index');
        }

        return [
            'success' => $swapped,
        ];
    }

    public function cart(Subscription $subscription)
    {
        if ($subscription->price <= 0) {
            return redirect()->route('user.subscription.index');
        }

        $page = (object) $this->pageService->show('user.subscription.cart', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }

    public function checkout(Subscription $subscription, SubscriptionPurchaseRequest $request)
    {
        (new SubscriptionService())->checkout($subscription, $request);
    }
}
