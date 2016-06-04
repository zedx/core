<?php

use Illuminate\Database\Seeder;
use ZEDx\Models\Adstatus;

class AdstatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['validate', 'pending', 'expired', 'banned', 'trashed'];
        foreach ($statuses as $status) {
            Adstatus::create(['title' => $status]);
        }
    }
}
