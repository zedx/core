<?php

use Illuminate\Database\Seeder;
use ZEDx\Models\Subscription;

class SubscriptionAdtypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subscriptions = Subscription::all();
        $subscriptionAdtypes = [
        1 => $this->getSubscriptionAdTypes([1, 15], [0, 0], [0, 0]),
        2 => $this->getSubscriptionAdTypes([1, 9999], [1, 15], [0, 0]),
        3 => $this->getSubscriptionAdTypes([1, 9999], [1, 9999], [1, 15]),
      ];

        foreach ($subscriptions as $subscription) {
            $this->syncAdtypesToSubscription($subscription, $subscriptionAdtypes[$subscription->id]);
        }
    }

    protected function getSubscriptionAdTypes($adtype1, $adtype2, $adtype3)
    {
        return [
        1 => ['enabled' => $adtype1[0], 'number' => $adtype1[1]],
        2 => ['enabled' => $adtype2[0], 'number' => $adtype2[1]],
        3 => ['enabled' => $adtype3[0], 'number' => $adtype3[1]],
      ];
    }

    protected function syncAdtypesToSubscription(Subscription $subscription, $adtypes)
    {
        array_walk($adtypes, function (&$adtype, $key) {
            $adtype['number'] = $adtype['enabled'] == 1 && $adtype['number'] > 0 ? $adtype['number'] : 0;
            unset($adtype['enabled']);
        });

        $subscription->adtypes()->sync($adtypes);
    }
}
