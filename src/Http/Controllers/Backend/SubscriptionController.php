<?php

namespace ZEDx\Http\Controllers\Backend;

use Illuminate\Support\Collection;
use ZEDx\Events\Subscription\SubscriptionWasCreated;
use ZEDx\Events\Subscription\SubscriptionWasUpdated;
use ZEDx\Events\Subscription\SubscriptionWillBeCreated;
use ZEDx\Events\Subscription\SubscriptionWillBeUpdated;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\SubscriptionRequest;
use ZEDx\Models\Subscription;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $subscriptions = Subscription::paginate(10);

        return view_backend('subscription.index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view_backend('subscription.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(SubscriptionRequest $request)
    {
        $subscription = new Subscription();
        $subscription->fill($request->all());

        event(
            new SubscriptionWillBeCreated($subscription)
        );

        $subscription->save();

        $this->syncAdtypes($subscription, $request);

        event(
            new SubscriptionWasCreated($subscription)
        );

        return redirect()->route('zxadmin.subscription.edit', $subscription->id);
    }

    protected function syncAdtypes(Subscription $subscription, SubscriptionRequest $request)
    {
        if ($request->has('adtypes')) {
            $adtypes = $request->get('adtypes');

            array_walk($adtypes, function (&$adtype, $key) {
                $adtype['number'] = $adtype['enabled'] == 1 && $adtype['number'] > 0 ? $adtype['number'] : 0;
                unset($adtype['enabled']);
            });

            $subscription->adtypes()->sync($adtypes);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit(Subscription $subscription)
    {
        $adtypes = $subscription->adtypes()->lists('number', 'adtype_id');

        return view_backend('subscription.edit', compact('subscription', 'adtypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update(Subscription $subscription, SubscriptionRequest $request)
    {
        $subscription->fill($request->all());

        event(
            new SubscriptionWillBeUpdated($subscription)
        );

        $subscription->save();
        $this->syncAdtypes($subscription, $request);

        event(
            new SubscriptionWasUpdated($subscription)
        );

        return redirect()->route('zxadmin.subscription.edit', $subscription->id)->with('message', 'success');
    }

    /**
     * Remove a Collection of Subscriptions.
     *
     * @param  Collection  $subscriptions
     *
     * @return Response
     */
    public function destroySubscriptionsCollection(Collection $subscriptions)
    {
        foreach ($subscriptions as $subscription) {
            $this->destroy($subscription);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Subscription  $subscription
     *
     * @return Response
     */
    protected function destroy(Subscription $subscription)
    {
        $subscription->delete();
    }
}
