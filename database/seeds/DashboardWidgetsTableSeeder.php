<?php

use Illuminate\Database\Seeder;
use ZEDx\Models\Dashboardwidget;

class DashboardWidgetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create('Backend\ZEDx\LatestMembers', 'Derniers utilisateurs inscrits', '[]', 1, 6);
        $this->create('Backend\ZEDx\LatestNotifications', 'Notifications', '[]', 2, 6);
        $this->create('Backend\ZEDx\LatestAds', 'Dernières annonces', '[]', 3, 12);
    }

    protected function create($namespace, $title, $config, $position, $size)
    {
        return Dashboardwidget::create([
        'namespace' => $namespace,
        'title'     => $title,
        'config'    => $config,
        'position'  => $position,
        'size'      => $size,
      ]);
    }
}
