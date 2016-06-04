<?php

use Illuminate\Database\Seeder;
use ZEDx\Models\Menu;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $my_ads = $this->create('Mes annonces', 'Mes annonces', 'user', 'fa fa-list-alt', '/user/ad', 'link');
        $add_new_ad = $this->create('Ajouter une annonce', 'Ajouter une annonce', 'user', 'fa fa-plus', '/user/adtype', 'link');
        $subscription = $this->create('Abonnements', 'Abonnements', 'user', 'fa fa-gift', '/user/subscription', 'link');
        $my_account = $this->create('Mon compte', 'Mon compte', 'user', 'fa fa-user', '/user/edit', 'link');

        $all_ads_header = $this->create('Toutes les annonces', 'Toutes les annonces', 'header', 'fa fa-search', '/ad', 'link');
    }

    protected function create($name, $title, $group, $icon, $link, $type)
    {
        return Menu::create([
        'name'       => $name,
        'title'      => $title,
        'group_name' => $group,
        'icon'       => $icon,
        'link'       => $link,
        'type'       => $type,
      ]);
    }
}
