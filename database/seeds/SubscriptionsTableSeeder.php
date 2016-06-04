<?php

use Illuminate\Database\Seeder;
use ZEDx\Models\Subscription;

class SubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create('Basic', 'Par jours', 30, 1, 0);
        $this->create('Premium', 'Par lots', 9999, 0, 10);
        $this->create('Gold', 'Intégral', 9999, 0, 50);
    }

    protected function create($title, $description, $days, $is_default, $price)
    {
        return Subscription::create([
        'title'       => $title,
        'description' => $description,
        'days'        => $days,
        'is_default'  => $is_default,
        'price'       => $price,
      ]);
    }
}
