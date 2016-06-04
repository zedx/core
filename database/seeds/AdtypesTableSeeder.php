<?php

use Illuminate\Database\Seeder;
use ZEDx\Models\Adtype;

class AdtypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createBasic();
        $this->createPremium();
        $this->createGold();
    }

    protected function createBasic()
    {
        Adtype::create([
        'title'            => 'Basic',
        'is_headline'      => false,
        'can_renew'        => false,
        'can_edit'         => false,
        'can_add_pic'      => true,
        'can_update_pic'   => true,
        'nbr_pic'          => 3,
        'nbr_days'         => 9999,
        'can_add_video'    => true,
        'nbr_video'        => 3,
        'can_update_video' => true,
        'price'            => 0,
      ]);
    }

    protected function createPremium()
    {
        Adtype::create([
        'title'            => 'Premium',
        'is_headline'      => false,
        'can_renew'        => true,
        'can_edit'         => true,
        'can_add_pic'      => true,
        'can_update_pic'   => true,
        'nbr_pic'          => 3,
        'nbr_days'         => 30,
        'can_add_video'    => true,
        'nbr_video'        => 3,
        'can_update_video' => true,
        'price'            => 2,
      ]);
    }

    protected function createGold()
    {
        Adtype::create([
        'title'            => 'Gold',
        'is_headline'      => true,
        'can_renew'        => true,
        'can_edit'         => true,
        'can_add_pic'      => true,
        'can_update_pic'   => true,
        'nbr_pic'          => 10,
        'nbr_days'         => 90,
        'can_add_video'    => true,
        'nbr_video'        => 5,
        'can_update_video' => true,
        'price'            => 4,
      ]);
    }
}
