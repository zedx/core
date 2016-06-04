<?php

use Illuminate\Database\Seeder;
use ZEDx\Models\Notification;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Notification::create([
        'actor_name'    => 'ZEDx',
        'actor_id'      => null,
        'actor_role'    => 'system',
        'notified_name' => 'Création du site par [ ZEDx ].',
        'notified_id'   => null,
        'action'        => 'create',
        'type'          => 'website',
        'is_visible'    => 1,
      ]);
    }
}
