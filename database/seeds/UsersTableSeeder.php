<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use ZEDx\Events\User\UserWasCreated;
use ZEDx\Models\Role;
use ZEDx\Models\Subscription;
use ZEDx\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::whereName('user')->firstOrFail();

        $subscription = Subscription::whereIsDefault(1)->first();
        $user = $role->users()->create([
        'name'                    => 'ZEDx demo',
        'email'                   => 'demo@zedx.io',
        'avatar'                  => 'https://pbs.twimg.com/profile_images/690567537591992320/lLsNP_Od.png',
        'phone'                   => '0606060606',
        'is_phone'                => true,
        'is_validate'             => true,
        'subscription_id'         => $subscription->id,
        'subscribed_at'           => Carbon::now()->format('d/m/Y'),
        'subscription_expired_at' => $subscription->days >= 9999 ? null : Carbon::now()->addDays($subscription->days + 1),
        'password'                => 'password',
      ]);
        event(new UserWasCreated($user, 'ZEDx'));

        $users = factory(User::class, 3)->create();

        foreach ($users as $user) {
            event(new UserWasCreated($user, 'ZEDx'));
        }
    }
}
