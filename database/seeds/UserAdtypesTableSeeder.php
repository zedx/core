<?php

use Illuminate\Database\Seeder;
use ZEDx\Models\User;

class UserAdtypesTableSeeder extends Seeder
{
    /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
      $users = User::all();
      foreach ($users as $user) {
          $subscription = $user->subscription;
          $adtypes = [];
          foreach ($subscription->adtypes as $adtype) {
              $adtypes[$adtype->id] = ['number' => $adtype->pivot->number];
          }
          $this->saveUserAdtype($user, $adtypes);
      }
  }

    protected function saveUserAdtype(User $user, $adtypes)
    {
        $user->adtypes()->sync($adtypes);
    }
}
