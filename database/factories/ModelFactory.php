<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
use Carbon\Carbon;
use ZEDx\Models\User;
use ZEDx\Models\Role;
use ZEDx\Models\Subscription;

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'name'        => $faker->name,
        'email'       => $faker->email,
        'is_validate' => true,
        'role_id'     => function () {
          return Role::whereName('user')->firstOrFail()->id;
        },
        'subscription_id' => function () {
          return Subscription::whereIsDefault(1)->first()->id;
        },
        'subscribed_at'           => Carbon::now()->format('d/m/Y'),
        'subscription_expired_at' => function () {
          $subscription = Subscription::whereIsDefault(1)->first();

          return $subscription->days >= 9999 ? null : Carbon::now()->addDays($subscription->days + 1);
        },
        'password'       => str_random(10),
        'remember_token' => str_random(10),
    ];
});

$factory->define(ZEDx\Models\Admin::class, function (Faker\Generator $faker) {
  return [
    'name'           => $faker->name,
    'email'          => $faker->email,
    'password'       => bcrypt(str_random(10)),
    'remember_token' => str_random(10),
  ];
});

$factory->define(ZEDx\Models\Subscription::class, function (Faker\Generator $faker) {
  return [
    'title'       => $faker->word,
    'description' => $faker->sentence,
    'days'        => $faker->randomNumber(3),
    'is_default'  => 0,
    'price'       => $faker->randomNumber(2),
  ];
});

$factory->define(ZEDx\Models\Adtype::class, function (Faker\Generator $faker) {
  return [
    'title'            => $faker->word,
    'is_headline'      => $faker->boolean(),
    'can_renew'        => $faker->boolean(),
    'can_edit'         => $faker->boolean(),
    'can_add_pic'      => $faker->boolean(),
    'can_update_pic'   => $faker->boolean(),
    'nbr_pic'          => $faker->randomNumber(1),
    'nbr_days'         => $faker->randomNumber(3),
    'can_add_video'    => $faker->boolean(),
    'nbr_video'        => $faker->randomNumber(1),
    'can_update_video' => $faker->boolean(),
    'price'            => $faker->randomNumber(2),
  ];
});

$factory->define(ZEDx\Models\Field::class, function (Faker\Generator $faker) {
  return [
    'name'         => $faker->word,
    'type'         => $faker->numberBetween(1, 8),
    'title'        => $faker->word,
    'unit'         => $faker->randomLetter().'²',
    'is_price'     => $faker->boolean(),
    'is_in_ad'     => $faker->boolean(),
    'is_in_search' => $faker->boolean(),
  ];
});
