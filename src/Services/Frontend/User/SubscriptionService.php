<?php

namespace ZEDx\Services\Frontend\User;

use Auth;
use Payment;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\SubscriptionPurchaseRequest;
use ZEDx\Jobs\swapSubscription;
use ZEDx\Models\Gateway;
use ZEDx\Models\Subscription;
use ZEDx\Models\User;

class SubscriptionService extends Controller
{
    protected $user;

    public function __construct()
    {
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
        $currency = setting('currency');
        $subscriptions = Subscription::all();
        $user_subscription = $this->user->subscription()->first();
        $user = $this->user;

        return [
            'data' => compact('subscriptions', 'user_subscription', 'user', 'currency'),
        ];
    }

    /**
     * Swap subscription.
     *
     * @return Response
     */
    public function swapForFree(Subscription $subscription)
    {
        if ($subscription->price != 0) {
            return false;
        }

        dispatch(
            new swapSubscription($subscription, $this->user)
        );

        return true;
    }

    public function cart(Subscription $subscription)
    {
        $currency = setting('currency');
        $gateways = Gateway::enabled()->get();

        if ($subscription->price > 0) {
            return [
                'data' => compact('subscription', 'currency', 'gateways'),
            ];
        }

        return [
            'data' => null,
        ];
    }

    public function checkout(Subscription $subscription, SubscriptionPurchaseRequest $request)
    {
        $transaction = [
            'gatewayId' => $request->get('gateway'),
            'userId'    => $this->user->id,
            'command'   => '\ZEDx\Jobs\purchaseSubscription',
            'item'      => [
                'id'          => $subscription->id,
                'amount'      => number_format($subscription->price, 2, '.', ''),
                'name'        => 'Abonnement '.$subscription->title,
                'description' => 'Abonnement '.$subscription->description,
                'currency'    => setting('currency'),
                'quantity'    => '1',
            ],
        ];

        return Payment::purchase($transaction);
    }
}
