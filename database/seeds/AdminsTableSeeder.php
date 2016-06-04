<?php

use Illuminate\Database\Seeder;
use ZEDx\Models\Role;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::whereName('root')->first();
        $role->admins()->create([
            'name'     => 'Administrator',
            'email'    => 'admin@example.com',
            'password' => 'password',
        ]);
    }
}
