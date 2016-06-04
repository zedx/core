<?php

use Illuminate\Database\Seeder;
use ZEDx\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
        ['name' => 'root', 'display_name' => 'Root', 'description' => 'Use this account with extreme caution. When using this account it is possible to cause irreversible damage to the system.', 'is_visible' => false],
        ['name' => 'system', 'display_name' => 'System', 'description' => 'This role is reserved to systems actions.', 'is_visible' => false],
        ['name' => 'administrator', 'display_name' => 'Administrator', 'description' => 'Full access to create, edit, and update everything.', 'is_visible' => true],
        ['name' => 'manager', 'display_name' => 'Manager', 'description' => 'Ability to manage ads.', 'is_visible' => true],
        ['name' => 'translator', 'display_name' => 'Translator', 'description' => 'Ability to translate the website.', 'is_visible' => true],
        ['name' => 'user', 'display_name' => 'User', 'description' => 'Ability to add ads.', 'is_visible' => false],
      ]);
    }
}
