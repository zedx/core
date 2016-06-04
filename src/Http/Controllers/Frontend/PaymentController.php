<?php

namespace ZEDx\Http\Controllers\Frontend;

use Payment;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Models\Order;
use ZEDx\Services\Frontend\PageService;
use ZEDx\Services\Frontend\PaymentService;

class PaymentController extends Controller
{
    /**
     * The page service instance.
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param  PaymentService  $service
     *
     * @return void
     */
    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }

    public function cancelPayment(Order $order)
    {
        $this->service->cancelPayment($order);

        return redirect()->route('payment.cancelled')->with('order', $order);
    }

    public function notifyPayment(Order $order)
    {
        $this->service->notifyPayment($order);
    }

    public function returnPayment(Order $order)
    {
        $item = $this->service->returnPayment($order);
        if ($item) {
            return redirect()->route('payment.accepted')->with('order', $order);
        }

        return redirect()->route('payment.cancelled')->with('order', $order);
    }

    public function accepted()
    {
        $page = (object) (new PageService())->show('payment.accepted', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }

    public function cancelled()
    {
        $page = (object) (new PageService())->show('payment.cancelled', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }
}
