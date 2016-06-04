<?php

use Illuminate\Database\Seeder;
use ZEDx\Utils\TemplateHelper;

class TemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TemplateHelper::saveTemplates('Default');
    }
}
